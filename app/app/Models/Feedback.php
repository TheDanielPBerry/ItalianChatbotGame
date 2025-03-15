<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Feedback extends Model
{
	public const FEEDBACK_TYPES = [
		'OFFENSIVE',
		'INCORRECT',
		'IMPROVEMENT',
		'OTHER',
	];

	protected $fillable = [
		'user_id',
		'message_id',
		'type',
		'feedback',
	];
}
