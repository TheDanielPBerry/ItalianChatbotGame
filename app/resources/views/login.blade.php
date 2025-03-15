@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="css/login.css" />
<h2 class="text-center form-title">
	Sign in to start practicing your Italian
</h2>
<form id="login_form" action="{{ url('/login') }}" method="POST">
	@csrf
	<div class="error-container">
		{{ $errors->first() ?? '' }}
	</div>

	<label for="email">Email:</label>
	<input type="email"
		name="email"
		id="email"
		value="{{ $email ?? '' }}"
		autofocus
		/>
	<div class="error-container">
		{{ $errors->login->first('email') }}
	</div>
	<label for="password">Password:</label>
	<input type="password"
		name="password"
		id="password"
		/>
	<div class="error-container">
		{{ $errors->login->first('password') }}
	</div>
	<div class="form-row">
		<button id="submit-button" type="submit">
			Login
		</button>
	</div>
	<div class="form-row">
		<a href="{{ route('register') }}">Need an Account? Get Started</a>
	</div>
</form>
@endsection
