@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="{{ url('css/game.css') }}" />
<canvas id="canvas" width="512" height="512"></canvas>
<div id="chat">
	<div id="chat-history">
	</div>
	<textarea id="input" rows="4" autofocus></textarea>
</div>
<script src="{{ url('js/scenes.js') }}"></script>
<script src="{{ url('js/game.js') }}"></script>
<script src="{{ url('js/chat.js') }}"></script>
@endsection
