<x-mail::message>
    # Reminder: Return Item

    <x-mail::button :url="''"> Button Text </x-mail::button>

    Dear {{ $user->name }},

    Please remember to return the Item {{ $borrow->item->item_code . $borrow->item->item_name }} you borrowed before the
    due date.

    Thank you.

    Thanks,<br />
    {{ config("app.name") }}
</x-mail::message>