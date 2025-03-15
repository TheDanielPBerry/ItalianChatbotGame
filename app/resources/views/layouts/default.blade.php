<!DOCTYPE html>
<html lang="it">
	<head>
		<title>La Vita Italiana</title>

		<link rel="stylesheet" href="{{ url('/css/app.css') }}" />
		<link rel="stylesheet" href="{{ url('/css/popup.css') }}" />
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<script type="text/javascript" src="{{ url('/js/popup.js') }}"></script>
	</head>
	<body>
		<header>
			<a href="/" id="title">
				La Vita Italiana
			</a>
			@if(Auth::check())
				<div id="account-section">
					<a class="help-link material-icons" onclick="OpenModal('help_modal')" style="position: relative; top: 5px; margin-right: 1em; font-size: 1.8em;" title="Help">help_outlined</a>
					<a href="{{ url('/logout') }}">Logout</a>
				</div>
			@endif
		</header>
		<hr />
		<section>
			@yield('content')
		</section>
	</body>
</html>
