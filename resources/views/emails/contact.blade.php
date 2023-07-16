<x-mail::message>
    {{ $data['subject'] }}
    ========================

    Name: {{ $data['name'] }}

    Email: {{ $data['email'] }}

    Message: {{ $data['message'] }}
</x-mail::message>
