{{-- @extends('layouts.app')

@section('content')
    <h1>Parsed Emails</h1>
    <div class="email-data" style="margin: 20px; line-height: 1.5; font-size: 1.25rem; color: #333333; word-wrap: break-word;">

        @if(count($emails) > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd;">
                        <th style="text-align: left; padding: 10px;">From</th>
                        <th style="text-align: left; padding: 10px;">Subject</th>
                        <th style="text-align: left; padding: 10px;">Date</th>
                        <th style="text-align: left; padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px;">{{ $email['email_from'] }}</td>
                            <td style="padding: 10px;">{{ $email['email_subject'] }}</td>
                            <td style="padding: 10px;">{{ $email['email_date'] }}</td>
                            <td style="padding: 10px;">
                                <button onclick="showModal('body', {{ $loop->index }})" style="padding: 5px 10px; font-size: 1rem; background-color: #007bff; color: #fff; border: none; cursor: pointer;">View Body</button>
                                <button onclick="showModal('parsed', {{ $loop->index }})" style="padding: 5px 10px; font-size: 1rem; background-color: #28a745; color: #fff; border: none; cursor: pointer;">View Parsed Data</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No emails found.</p>
        @endif
    </div>

    <div id="emailModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000; border-radius: 8px; width: 60%; max-height: 80%; overflow-y: auto;">
        <h2 id="modalTitle"></h2>
        <div id="modalContent" style="margin-top: 20px; white-space: pre-wrap; line-height: 1.5; font-family: 'Courier New', monospace; font-size: 1rem; color: #333;"></div>
        <button onclick="closeModal()" style="margin-top: 20px; padding: 10px 20px; font-size: 1rem; background-color: #dc3545; color: #fff; border: none; cursor: pointer;">Close</button>
    </div>

    <script>
        const emails = @json($emails);

        function showModal(type, index) {
            const email = emails[index];
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            const modal = document.getElementById('emailModal');

            if (type === 'body') {
                modalTitle.innerText = 'Email Body';
                modalContent.innerText = email.body;
            } else if (type === 'parsed') {
                modalTitle.innerText = 'Parsed Data';
                modalContent.innerText = JSON.stringify(email.parsed_data, null, 4);
            }

            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('emailModal');
            modal.style.display = 'none';
        }
    </script>
@endsection
 --}}

 @extends('layouts.app')

@section('content')
    <h1>Logistic Data</h1>
    <div class="email-data" style="margin: 20px; line-height: 1.5; font-size: 1.25rem; color: #333333; word-wrap: break-word;">

        @if(count($logistics) > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd;">
                        <th style="text-align: left; padding: 10px;">From</th>
                        <th style="text-align: left; padding: 10px;">Date</th>
                        <th style="text-align: left; padding: 10px;">Subject</th>
                        <th style="text-align: left; padding: 10px;">Body</th>
                        <th style="text-align: left; padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logistics as $logistic)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px;">{{ $logistic->email_from ?? "null" }}</td>
                            <td style="padding: 10px;">{{ $logistic->email_date ?? "null" }}</td>
                            <td style="padding: 10px;">{{ substr($logistic->email_subject, 0, 20).'...' ?? "null" }}</td>
                            <td style="padding: 10px;">{{ substr($logistic->email_body, 0, 20).'...'  }}</td>
                            <td style="padding: 10px;">
                                <button onclick="showModal('body', {{ $logistic->id }})" style="padding: 5px 10px; font-size: 1rem; background-color: #007bff; color: #fff; border: none; cursor: pointer;">View Details</button>
                                {{-- <button onclick="showModal('parsed', {{ $logistic->id }})" style="padding: 5px 10px; font-size: 1rem; background-color: #28a745; color: #fff; border: none; cursor: pointer;">View Parsed Data</button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No logistic data found.</p>
        @endif
    </div>

    {{-- Modals for Logistic Details --}}
    <div id="emailModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000; border-radius: 8px; width: 60%; max-height: 80%; overflow-y: auto;">
        <h2 id="modalTitle"></h2>
        <div id="modalContent" style="margin-top: 20px; white-space: pre-wrap; line-height: 1.5; font-family: 'Courier New', monospace; font-size: 1rem; color: #333;"></div>
        <button onclick="closeModal()" style="margin-top: 20px; padding: 10px 20px; font-size: 1rem; background-color: #dc3545; color: #fff; border: none; cursor: pointer;">Close</button>
    </div>

    <script>
        const logistics = @json($logistics);

        function showModal(type, id) {
            const logistic = logistics.find(item => item.id === id);
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            const modal = document.getElementById('emailModal');

            if (type === 'body') {
                modalTitle.innerText = 'Logistic Details';
                modalContent.innerHTML = `
                    <strong>From:</strong> ${logistic.email_from}<br>
                    <strong>Request Type:</strong> ${logistic.request_type}<br>
                    <strong>Origin:</strong> ${logistic.origin}<br>
                    <strong>Destination:</strong> ${logistic.destination}<br>
                    <strong>Transport Mode:</strong> ${logistic.transport_mode}<br>
                    <strong>Container Type:</strong> ${logistic.container_type}<br>
                    <strong>Cargo Weight (kg):</strong> ${logistic.cargo_weight_kg}<br>
                    <strong>Cargo Type:</strong> ${logistic.cargo_type}<br>
                    <strong>Additional Requirements:</strong> ${logistic.additional_requirements}<br>
                    <strong>Status:</strong> ${logistic.status}<br>
                    <strong>Message:</strong> ${logistic.message}
                `;
            } else if (type === 'parsed') {
                modalTitle.innerText = 'Parsed Data';
                modalContent.innerText = JSON.stringify(logistic, null, 4);
            }

            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('emailModal');
            modal.style.display = 'none';
        }
    </script>
@endsection
