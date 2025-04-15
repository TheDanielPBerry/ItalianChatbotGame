<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rasa extends Model
{

	public function __construct()
	{
		$domain = yaml_parse_file(env('RASA_DOMAIN_PATH'));
		$this->responses = $domain['responses'];
	}

	public function getActionText(string $action): string
	{
		if(!isset($this->responses[$action])) {
			return "Mi dispiace, non capisco la tua domanda.";
		}
		$resp = $this->responses[$action];
		if(empty($resp) || empty($resp[0]) || empty($resp[0]['text'])) {
			return "Mi dispiace, non capisco la tua domanda.";
		}
		return $resp[0]['text'];
	}
}
