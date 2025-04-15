<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Message;
use App\Models\Rasa;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class GameController extends Controller
{
	public function test()
	{
		$rasa = new Rasa();
	}

	public function game()
	{
		if(!Auth::check()) {
			return redirect('/');
		}
		$user = Auth::user();
		$viewbag = [
			'surveyed' => empty($user->survey) ? 0 : 1,
		];
		return view('game', $viewbag);
	}

	public function chat(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'message' => ['required', 'string', 'max:128'],
			'chat' => ['required', 'string', 'max:32'],
		]);
		if($validator->fails()) {
			return abort(422);
		}

		if(!Auth::check()) {
			return abort(401);
		}

		$user = Auth::user();
		if($user->shouldThrottle()) {
			return response()->json(['errors' => ['The maximum number of interactions has been reached for today.']], 429);
		}

		$validated = $validator->validate();
		$conversationId = Message::GetConversationId($user->conversation_token, $validated['chat']);
		$conversation = Message::where('conversation_id', $conversationId)->get();

		if(empty($conversation)) {
			//Initiate conversation
		}


		//Insert latest message into db
		$message = new Message();
		$message->user_id = $user->id;
		$message->conversation_id = $conversationId;
		$message->message = $validated['message'];

		$actions = $message->processPrompt();
		if(isset($actions['error'])) {
			//Do nothing right now
		}

		$response = [
			'message_id' => $message->id,
			'intent' => $message->intent,
			'message_error' => $message->message_error,
			'action' => $message->action,
			'prediction_error' => $message->prediction_error,
			'response' => $message->response,
			'grammar' => $message->grammar_response,
			'grammar_error' => $message->grammar_error,
			'actions' => $actions,
		];
		return response()->json($response);
	}

	public function feedback(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'feedback' => ['required', 'string', 'max:512'],
			'message_id' => ['required', 'integer'],
			'feedback_type' => ['required', 'integer'],
		]);
		if($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}
		$validated = $validator->validate();
		if($validated['feedback_type'] < 0 || $validated['feedback_type'] >= count(Feedback::FEEDBACK_TYPES)) {
			return response()->json(['errors' => ["Invalid feedback type"]], 422);
		}

		if(!Auth::check()) {
			return abort(401);
		}
		$user = Auth::user();
		if(Feedback::shouldThrottle($user)) {
			return response()->json(['errors' => ['The maximum number of feedback submissions has been reached for today.']], 429);
		}

		//Verify user has access to the conversation
		if(Message::BelongsToConversation($user, $validated['message_id']) === false) {
			return response()->json(['errors' => ['forbidden']], 403);
		}

		$feedback = new Feedback();
		$feedback->user_id = $user->id;
		$feedback->message_id = $validated['message_id'];
		$feedback->feedback = $validated['feedback'];
		$feedback->type = $validated['feedback_type'];
		$feedback->save();
		return response()->json(['success' => 1]);
	}


	public function survey(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'out_of_place_dialogue' => ['string', 'max:512'],
			'progression_of_speech' => ['string', 'max:512'],
			'lost_steps' => ['string', 'max:512'],
			'recommendations' => ['string', 'max:512'],
			'unclear_instruction' => ['string', 'max:512'],
			'satisfaction' => ['string', 'max:512'],
			'desired_interactions' => ['string', 'max:512'],
		]);
		if($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}
		$validated = $validator->validate();

		if(!Auth::check()) {
			return abort(401);
		}
		$user = Auth::user();
		if(!empty($user->survey)) {
			return response()->json(['errors' => ['User has already submitted a survey.']], 208);
		}


		$survey = new Survey();
		$survey->user_id = $user->id;
		$survey->content = json_encode($validated, JSON_INVALID_UTF8_SUBSTITUTE);
		$survey->save();
		return response()->json(['success' => 1]);
	}
}
