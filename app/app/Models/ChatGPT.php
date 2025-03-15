<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGPT extends Model
{
	public const OPEN_AI_URL = 'https://api.openai.com/v1/%s';

	public static function CheckGrammar(Message $message): array
	{
		$url = sprintf(
			static::OPEN_AI_URL,
			'responses'
		);

		$input = sprintf('Analyze this Italian sentence for any grammatical errors: "%s". If the sentence is primarily in English please respond with the single word: "english". If the sentence is grammattically correct, please only respond with a single word: "yes". If it is gramatically incorrect, provide only the analysis.', $message->message);
		$payload = [
			'model' => 'gpt-4o-mini',
			'input' => $input,
		];
		$post_fields = json_encode($payload);
		if($post_fields === false) {
			return ['error' => json_last_error_msg()];
		}

		$authorization_header = sprintf('Authorization: Bearer %s', env('CHAT_GPT_API_TOKEN'));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			$authorization_header,
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
}
