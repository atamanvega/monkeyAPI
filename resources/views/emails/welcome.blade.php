@component('mail::message')
	Hi {{$user->name}}
	Thanks for creating an account. Please verify your account clicking on the following link:
	@component('mail::button', ['url' => route('verify', $user->verification_token)])
		Confirm my email
	@endcomponent

	Thanks,<br>
	{{ config('app.name') }}
@endcomponent