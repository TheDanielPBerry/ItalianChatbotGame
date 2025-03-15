<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

enum FeedbackType: int {
	case OFFENSIVE = 0;
	case INCORRECT = 1;
	case IMPROVEMENT = 2;
	case OTHER = 3;
};

class Feedback extends Model
{
	protected $fillable = [
		'user_id',
		'message_id',
		'type',
		'feedback',
	];
}
