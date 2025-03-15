<?php

namespace App\Http\Controllers;

use App\Models\ChatGPT;
use App\Models\Feedback;
use App\Models\FeedbackType;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class GameController extends Controller
{
	public function game()
	{
		if(!Auth::check()) {
			return redirect('/');
		}
		return view('game');
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



		$grammar_analysis = ChatGPT::CheckGrammar($message);
		if(empty($grammar_analysis['error'])) {
			$message->grammar_response = $grammar_analysis['output'][0]['content'][0]['text'];
		} else {
			$message->grammar_response = $grammar_analysis;
		}
		if(trim(strtolower($message->grammar_response)) === 'english') {
			$prediction = 'Mi dispiace, non so parlare inglese.';
		} else {
			$prediction = $message->processPrompt();
		}


		$message->save();

		$response = [
			'message_id' => $message->id,
			'prediction' => $prediction,
			'grammar' => $message->grammar_response,
		];
		return response()->json($response);
	}

	public function feedback(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'feedback' => ['required', 'string', 'max:512'],
			'message_id' => ['required', 'integer'],
			'type' => ['required', 'integer'],
		]);
		if($validator->fails()) {
			return abort(422);
		}
		$validated = $validator->validate();
		if(is_null(FeedbackType::tryFrom($validated['type']))) {
			return abort(422);
		}

		if(!Auth::check()) {
			return abort(401);
		}
		$user = Auth::user();

		//Verify user has access to the conversation
		if(Message::BelongsToConversation($user, $validated['message_id']) === false) {
			return abort(403);
		}

		$feedback = new Feedback();
		$feedback->user_id = $user->id;
		$feedback->message_id = $validated['message_id'];
		$feedback->feedback = $validated['feedback'];
		$feedback->type = $validated['type'];
	}
}
