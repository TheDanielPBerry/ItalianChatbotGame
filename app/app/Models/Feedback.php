<?php

namespace App\Models;

use Carbon\Carbon;
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

	public static function shouldThrottle(User $user): bool
	{
		$feedbacks_today = static::where('user_id', $user->id)->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->count();
		return $feedbacks_today >= 10;
	}
}
