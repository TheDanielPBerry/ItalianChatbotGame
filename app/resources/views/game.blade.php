@extends('layouts.default')
@section('content')
<meta id="chat-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="{{ url('css/game.css') }}" />
<link rel="stylesheet" href="{{ url('css/ellipsis.css') }}" />
<canvas id="canvas" width="512" height="512"></canvas>
<div id="chat">
	<div id="chat-history">
		<div class="dot-flashing hide" id="throbber" style="float: left; clear: right;"></div>
	</div>
	<textarea id="input" rows="4" autofocus disabled></textarea>
	<div style="text-align: right;">
		<button class="material-icons" id="send" disabled>send</button>
	</div>
</div>
<hr>
<div>
	<h3>Grammatica</h3>
	<p id="grammar">
	</p>
</div>

<div class="overlay hide" id="feedback_modal">
	<div class="modal">
		<div class="modal-title">
			Report Feedback
			<span class="modal-close material-icons">close</span>
		</div>
		<div class="modal-body">
			<hr/>
			<form id="feedback-form">
				<input type="hidden" id="feedback_message_id" name="message_id" value="" />
				<select id="feedback_type" name="feedback_type" required>
					<option disabled selected value="-1">-- Please Select a type of Feedback --</option>
					<option value="0">Offensive Content</option>
					<option value="1">Incorrect Grammar</option>
					<option value="2">Suggest an Improvement</option>
					<option value="3">Other</option>
				</select>
				<textarea name="feedback" required maxlength="256"></textarea>
				<button type="submit" id="submit-feedback">Submit Feedback</button>
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
				<img src="{{ url('/img/help/help_1.gif') }}" style="max-width: 100%;" />
			</p>
			<hr/>
			<h3>Characters</h3>
			<p>
				Find a character to interact with in the world and initiate a conversation.
				<img src="{{ url('/img/help/help_2.gif') }}" style="max-width: 100%;" />
			</p>
			<hr/>
			<h3>Leaving</h3>
			<p style="text-align: left;">
				Once finished with a conversation, say good bye and you can navigate back to the outside world.
				<img src="{{ url('/img/help/help_3.gif') }}" style="max-width: 100%;" />
			</p>
		</div>
	</div>
</div>
<div class="overlay hide" id="survey_modal">
	<div class="modal">
		<div class="modal-title">
			Please Consider Taking<br/>
			this Short Freeform Survey
			<span class="modal-close material-icons">close</span>
		</div>
		<div class="modal-body">
			<form id="survey-form">
				<input type="hidden" id="surveyed" name="surveyed" value="{{ $surveyed }}" />
				<ol>
					<li>
						<ol style="list-style-type: lower-alpha;">
							<li>
								<label>
									Did you experience any out of place dialogue during the interactions?
								</label>
								<input type="text" name="out_of_place_dialogue" maxlength="512" />
							</li>
							<li>
								<label>
									If so, do you feel as it was a natural progression of speech?
								</label>
								<input type="text" name="progression_of_speech" maxlength="512" />
							</li>
						</ol>
					</li>
					<li>
						<ol style="list-style-type: lower-alpha;">
							<li>
								<label>
									Did you ever get stuck or not know what steps to take next?
								</label>
								<input type="text" name="lost_steps" maxlength="512" />
							</li>
							<li>
								<label>
									Do you have any recommendations on how you would improve?
								</label>
								<input type="text" name="recommendations" maxlength="512" />
							</li>
						</ol>
					</li>
					<li>
						<ol style="list-style-type: lower-alpha;">
							<li>
								<label>
									Were the instructions ever unclear when using the application?
								</label>
								<input type="text" name="unclear_instruction" maxlength="512" />
							</li>
						</ol>
					</li>
					<li>
						<ol style="list-style-type: lower-alpha;">
							<li>
								<label>
									Do you feel as though your Italian conversation skills have improved?
								</label>
								<input type="text" name="satisfaction" maxlength="512" />
							</li>
						</ol>
					</li>
					<li>
						<ol style="list-style-type: lower-alpha;">
							<li>
								<label>
									What types of interactions were missing that would you like to be able to practice?
								</label>
								<input type="text" name="desired_interactions" maxlength="512" />
							</li>
						</ol>
					</li>
				</ol>
				<button type="submit" id="submit-survey">Submit</button>
			</form>
		</div>
	</div>
</div>


<script src="{{ url('js/scenes.js') }}"></script>
<script src="{{ url('js/chat.js') }}"></script>
<script src="{{ url('js/game.js') }}"></script>
<script src="{{ url('js/survey.js') }}"></script>
@endsection
