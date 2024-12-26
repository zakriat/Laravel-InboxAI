<?php

use Illuminate\Support\Facades\Route;
use SabatinoMasala\Replicate\Replicate;
use GeminiAPI\Laravel\Facades\Gemini;
use App\Http\Controllers\AiParserController;

Route::get('/emails', [AiParserController::class, 'showEmails'])->name('emails.index');
Route::get('/parsed-emails', [AiParserController::class, 'showParsedEmails'])->name('emails.parsed');

Route::get('/', function () {
    // $token = env('REPLICATE_TOKEN');
    // $replicate = new Replicate($token);
    // $output = $replicate->run('meta/meta-llama-3-70b-instruct', [
    //     'prompt' => 'Johnny has 8 billion parameters. His friend Tommy has 70 billion parameters. What does this mean when it comes to speed?'
    // ]);
    // $sentence = implode($output);
    $sen =  Gemini::embedText('PHP in less than 100 chars');
    dd($sen);
});


