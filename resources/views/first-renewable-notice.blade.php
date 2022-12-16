@component('mail::message')
    Dear {{ $email }},

    Your subscription is expiring soon.
    Renew them now.

    Your subscription is expiring on {{ $renewdate }}.
    {{--  <button>login</button>  --}}
   @component('mail::button', ['url' => 'https://gbho.festnepal.com.np/login'])
    Login Now
    @endcomponent

@endcomponent

