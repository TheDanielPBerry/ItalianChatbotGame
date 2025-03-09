<!DOCTYPE html>
<html lang="it">
	<head>
		<title>La Vita Italiana</title>
		<link rel="stylesheet" href="css/app.css" />
	</head>
	<body>
		<header>
			<a href="/" id="title">
				La Vita Italiana
			</a>
			@if(Auth::check())
				<div id="account-section">
					<a href="{{ url('/logout') }}">Logout</a>
				</div>
			@endif
		</header>
		<section>
			@yield('content')
		</section>

	</body>
</html>

