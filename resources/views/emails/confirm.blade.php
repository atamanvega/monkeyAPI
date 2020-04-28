@component('mail::message')
	Hi {{$user->name}}
	You have changed your email account. Please verify it clicking on the following button:
	@component('mail::button', ['url' => route('verify', $user->verification_token)])
		Confirm my email
	@endcomponent

	Thanks,<br>
	{{ config('app.name') }}
@endcomponent