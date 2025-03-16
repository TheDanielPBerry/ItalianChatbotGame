<?php

namespace App\Models;


use Carbon\Carbon;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'conversation_token',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	public function shouldThrottle(): bool
	{
		$max_messages = env('MAX_NUMBER_OF_ALLOWED_MESSAGES_PER_DAY', Message::MAX_NUMBER_OF_ALLOWED_MESSAGES_PER_DAY);
		$messages_today = Message::where('user_id', $this->id)->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->count();
		return $messages_today >= $max_messages;
	}
}
