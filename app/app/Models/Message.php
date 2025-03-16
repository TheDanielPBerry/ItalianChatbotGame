<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	private const RASA_BASE_URL = 'http://%s:%s/conversations/%s/%s?token=%s';
	public const MAX_NUMBER_OF_ALLOWED_MESSAGES_PER_DAY = 80;
	private string $host, $port, $token;

	public function __construct()
	{
		$this->host = env('RASA_HOST');
		$this->port = env('RASA_PORT');
		$this->token = env('RASA_TOKEN');
	}

	protected $fillable = [
		'conversation_id',
		'message',
		'user_id',
		'updated',
		'response',
		'action',
		'intent',
	];

	public static function GetConversationId(string $conversationToken, string $chatName): string
	{
		return sprintf('%s-%s', $conversationToken, $chatName);
	}

	public function getPrediction(): array
	{
		$url = sprintf(
			static::RASA_BASE_URL,
			$this->host,
			$this->port,
			$this->conversationId,
			'predict',
			$this->token
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response = curl_exec($ch);

		if(curl_error($ch)) {
			return ['error' => curl_error($ch)];
		}

		$resp = json_decode($response, true);
		curl_close($ch);

		if($resp === false) {
			return ['error' => json_last_error_msg()];
		}
		return $resp;
	}

	public function sendMessage(): array
	{
		$url = sprintf(
			static::RASA_BASE_URL,
			$this->host,
			$this->port,
			$this->conversationId,
			'messages',
			$this->token
		);

		$payload = [
			'text' => $this->message,
			'sender' => 'user',
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

		$response = curl_exec($ch);

		if(curl_error($ch)) {
			return ['error' => curl_error($ch)];
		}

		$resp = json_decode($response, true);
		curl_close($ch);

		if($resp === false) {
			return ['error' => json_last_error_msg()];
		}
		return $resp;
	}


	public function processPrompt()
	{
		$grammar_analysis = ChatGPT::CheckGrammar($this);
		if(empty($grammar_analysis['error'])) {
			$this->grammar_response = $grammar_analysis['output'][0]['content'][0]['text'];
		} else {
			$this->grammar_error = $grammar_analysis['error'];
		}
		if(trim(strtolower($this->grammar_response)) === 'english') {
			$this->prediction = 'Mi dispiace, non so parlare inglese.';
			$this->prediction_error = 'english';
			return;
		}


		$messageResponse = $this->sendMessage();
		if(empty($messageResponse['error'])) {
			$this->intent = $messageResponse['latest_message']['intent']['name'];
		} else {
			$this->message_error = $messageResponse['error'];
		}

		$predictionResponse = $this->getPrediction();
		if(empty($predictionResponse['error'])) {
			$this->response = json_encode($predictionResponse);
		} else {
			$this->prediction_error = $predictionResponse['error'];
		}
		$this->save();
	}

	public static function BelongsToConversation(User $user, int $id): bool
	{
		return empty(static::where('id', $id)->where('user_id', $user->id)->first()) === false;
	}
}
