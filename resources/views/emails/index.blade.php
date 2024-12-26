@extends('layouts.app')

@section('content')
    <h1>Emails</h1>

    {{-- {{ dd($emailData) }} --}}
    @if(count($emailData) > 0)
        <ul>
            @foreach($emailData as $email)
                <li>
                    <strong>From:</strong> {{ $email['from'] }}<br>
                    <strong>To:</strong> {{ $email['to'] }}<br>
                    <strong>Subject:</strong> {{ $email['subject'] }}<br>
                    <strong>Date:</strong> {{ $email['date'] }}<br>
                    <hr>
                    <p>{{ $email['body']['text'] }}</p>
                </li>
            @endforeach
        </ul>
    @else
        <p>No emails found.</p>
    @endif

@endsection
