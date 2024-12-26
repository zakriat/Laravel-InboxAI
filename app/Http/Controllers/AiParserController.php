<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use GeminiAPI\Laravel\Facades\Gemini;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\LogisticData;



class AiParserController extends Controller
{
    public function showEmails(): View
    {
        // try {
            $client = Client::make([
                'host' => env('IMAP_HOST'),
                'port' => env('IMAP_PORT'),
                'encryption' => env('IMAP_ENCRYPTION'),
                'validate_cert' => env('IMAP_VALIDATE_CERT'),
                'username' => env('IMAP_USERNAME'),
                'password' => env('IMAP_PASSWORD'),
                'protocol' => 'imap',
                'timeout' => 100,
            ]);

            $client->connect();


            $inbox = $client->getFolder('INBOX');

            // dd($inbox);

            $messages = $inbox->messages()
            ->whereSince(now()->subDays(30))
            ->limit(10)
            ->setFetchOrder('desc')
            ->get();


            // dd($messages);

            $emailData = [];


            // dd($messages->getBody(),$messages->getTextBody(),$messages->getHTMLBody());

            foreach ($messages as $message) {
                $emailData[] = [
                    'from' => $message->getFrom(),
                    'to' => $message->getTo(),
                    'subject' => $message->getSubject(),
                    'date' => $message->getDate(),
                    'body' => [
    'text' => $message->getTextBody()
    // 'html' => $message->getHTMLBody()
]
                ];
            }


            // dd($emailData);

            return view('emails.index', compact('emailData'));
        // } catch (\Webklex\IMAP\Exceptions\ConnectionException $e) {
        //     Log::error("Connection failed: " . $e->getMessage());
        //     return view('emails.error', ['error' => 'Connection failed.']);
        // } catch (\Webklex\IMAP\Exceptions\AuthenticationException $e) {
        //     Log::error("Authentication failed: " . $e->getMessage());
        //     return view('emails.error', ['error' => 'Authentication failed.']);
        // } catch (\Webklex\IMAP\Exceptions\NoMessagesFoundException $e) {
        //     Log::warning("No emails found in the inbox.");
        //     return view('emails.error', ['error' => 'No emails found.']);
        // } catch (\Exception $e) {
        //     Log::error("An unexpected error occurred: " . $e->getMessage());
        //     return view('emails.error', ['error' => 'An unexpected error occurred.']);
        // }
    }

    public function showParsedEmails()
    {
        try {
            $client = Client::make([
                'host' => env('IMAP_HOST'),
                'port' => env('IMAP_PORT'),
                'encryption' => env('IMAP_ENCRYPTION'),
                'validate_cert' => env('IMAP_VALIDATE_CERT'),
                'username' => env('IMAP_USERNAME'),
                'password' => env('IMAP_PASSWORD'),
                'protocol' => 'imap',
                'timeout' => 100,
            ]);

            $client->connect();


            $inbox = $client->getFolder('INBOX');


            $messages = $inbox->messages()
            ->whereSince(now()->subDays(30))
            ->limit(10)
            ->setFetchOrder('desc')
            ->get();

            $parsedEmails = [];
            
            foreach ($messages as $message) {
                $emailBody = $message->getTextBody();

                $uid = $message->getUid();

                $prompt = <<<EOT
                You are a system for extracting structured logistics details from emails. Analyze the following email text and return a JSON object with the following fields:
                    - "from" (email address of the sender).
                    - "status" ("success" if all fields are present, "incomplete" otherwise).
                    - "message" (a short message summarizing the extraction result, e.g., "Relevant data found in the email body.").
                    - "request_type" (e.g., "price_quote", "shipment_update", "Shipping").
                    - "transport_mode" (e.g., "sea", "air", "road").
                    - "container_type" (e.g., "20ft", "40ft").
                    - "cargo_weight_kg" (shipment weight in kilograms).
                    - "cargo_type" (e.g., "Electronic components", "Furniture").
                    - "origin" (shipment start location).
                    - "destination" (shipment end location).
                    - "additional_requirements" (any specific instructions or requirements, e.g., "None specified").

                Rules:
                1. If any field is missing, exclude it from the JSON.
                2. Always return valid JSON.
                3. Do not include irrelevant information or comments.

                Email Content:
                "{$emailBody}"

                Respond only with the JSON object.
                EOT;

                // Use Gemini to generate text from the email body
                $generatedText = Gemini::generateText($prompt);

                // $parsedData = json_decode($generatedText, true);
                $cleanedText = trim(str_replace(['```json', '```'], '', $generatedText));

                // Parse the JSON
                $parsedData = json_decode($cleanedText, true);

                // If parsing failed, log the error and raw text
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('JSON Parse Error: ' . json_last_error_msg());
                    Log::error('Raw Text: ' . $generatedText);
                    Log::error('Cleaned Text: ' . $cleanedText);
                }

                LogisticData::updateOrCreate(
                    [
                        // Unique constraint: use the UID to identify the email
                        'email_uid' => $uid,
                    ],
                    [
                        // Fields to update or insert
                        'request_type' => $parsedData['request_type'] ?? null,
                        'origin' => $parsedData['origin'] ?? null,
                        'destination' => $parsedData['destination'] ?? null,
                        'cargo_weight_kg' => $parsedData['cargo_weight_kg'] ?? null,
                        'container_type' => $parsedData['container_type'] ?? null,
                        'transport_mode' => $parsedData['transport_mode'] ?? null,
                        'status' => $parsedData['status'] ?? null,
                        'message' => $parsedData['message'] ?? null,
                        'cargo_type' => $parsedData['cargo_type'] ?? null,
                        'additional_requirements' => $parsedData['additional_requirements'] ?? null,
                        'email_from' => $message->getFrom()[0]->mail ?? null,
                        'email_to' => $message->getTo()[0]->mail ?? null,
                        'email_subject' => $message->getSubject(),
                        'email_date' => $message->getDate(),
                        'email_body' => $emailBody,
                    ]
                );

            //     $parsedEmails[] = [
            //         // dd( $parsedData),
            //         'from' => $message->getFrom(),
            //         'to' => $message->getTo(),
            //         'subject' => $message->getSubject(),
            //         'date' => $message->getDate(),
            //         'body' => $message->getTextBody(),
            //         'parsed_data' => $parsedData,
            //     ];

            }

            $logisticData = LogisticData::all();


            return view('emails.parsed', ['logistics' => $logisticData]);
        } catch (\Exception $e) {
            Log::error("Error fetching or parsing emails: " . $e->getMessage());
            // return view('emails.error');
        }
    }

}
