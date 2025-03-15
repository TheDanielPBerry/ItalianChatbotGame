@extends('layouts.default')
@section('content')
<meta id="chat-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="{{ url('css/game.css') }}" />
<link rel="stylesheet" href="{{ url('css/ellipsis.css') }}" />
<canvas id="canvas" width="512" height="512"></canvas>
<div id="chat">
	<div id="chat-history">
		<div class="dot-flashing" id="throbber"></div>
	</div>
	<textarea id="input" rows="4" autofocus></textarea>
</div>
<div id="grammar-check">
</div>
<script src="{{ url('js/scenes.js') }}"></script>
<script src="{{ url('js/chat.js') }}"></script>
<script src="{{ url('js/game.js') }}"></script>

<div class="overlay hide" id="feedback_modal">
	<div class="modal">
		<div class="modal-title">
			Report Feedback
			<span class="modal-close material-icons">close</span>
		</div>
		<div class="modal-body">
			<form>
				<div class="form-group">
					<select id="feedback_type" name="feedback_type">
						<option value="Offensive Content"></option>
						<option value="Incorrect Grammar"></option>
						<option value=""></option>
						<option value=""></option>
					</select>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="overlay hide" id="help_modal">
	<div class="modal">
		<div class="modal-title">
			Instructions
			<span class="modal-close material-icons">close</span>
		</div>
		<div class="modal-body">
			<h3>Navigation</h3>
			<p>
				Move the mouse around the screen to find different elements on the map to interact with.
				<img src="{{ url('/img/help/help_1.gif') }}" style="" />
			</p>
			<hr/>
			<h3>Characters</h3>
			<p>
				Find a character to interact with in the world and initiate a conversation.
				<img src="{{ url('/img/help/help_2.gif') }}" style="" />
			</p>
			<hr/>
			<h3>Leaving</h3>
			<p style="text-align: left;">
				Once finished with a conversation, say good bye and you can navigate back to the outside world.
				<img src="{{ url('/img/help/help_3.gif') }}" style="" />
			</p>
		</div>
	</div>
</div>
@endsection
