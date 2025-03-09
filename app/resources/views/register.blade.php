@extends('layouts.default')
@section('content')

<link rel="stylesheet" href="{{ url('/css/login.css') }}" />
<hr />
<h2 class="text-center form-title">
	Create an account to start practicing your Italian
</h2>
<form id="register_form" action="{{ url('/register') }}" method="POST">
	@csrf

	<label for="fullname">Full Name:</label>
	<input type="text"
		name="fullname"
		id="fullname"
		value="{{ $fullname ?? '' }}"
		/>
	<div class="error-container">
		{{ $errors->register->first('fullname') }}
	</div>

	<label for="email">Email:</label>
	<input type="email"
		name="email"
		id="email"
		value="{{ $email ?? '' }}"
		/>
	<div class="error-container">
		{{ $errors->register->first('email') }}
	</div>

	<label for="password">Password:</label>
	<input type="password"
		name="password"
		id="password"
		/>
	<div class="error-container">
		{{ $errors->register->first('password') }}
	</div>

	<label for="password_confirmation">Confirm Password:</label>
	<input type="password"
		name="password_confirmation"
		id="password_confirmation"
		/>

	<p class="error-container hide" id="validate-msg"></p>

	<div class="form-row">
		<button type="submit" id="submit-button">
			Register
		</button>
	</div>
	<div class="form-row">
		<a href="{{ route('login') }}">Already have an account? Sign In</a>
	</div>
</form>

<script src="{{ url('/js/register.js') }}"></script>

@endsection
