@component('mail::message')
# Welcome to Kofsoft, {{ $name }}! 🎉

We’re excited to have you on board.

Your **{{ $plan }} plan** is now active, and your free trial ends on **{{ $trialEnds }}**.

You can log in anytime to manage your restaurant, view analytics, and explore our features.

@component('mail::button', ['url' => 'https://kofsoft.com/dashboard'])
Go to Dashboard
@endcomponent

Thanks for joining us,<br>
**The Kofsoft Team**
@endcomponent
