<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableAuthenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends AuthenticatableAuthenticatable
{
	use HasUlids, HasFactory, Notifiable;

	protected $table = 'users';
	protected $primaryKey = 'ulid';
	protected $rememberTokenName = 'token';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'ulid',
		'email',
		'password',
		'token',
		'nickname',
		'picture',
		'phone',
		'status',
		'last_login_at',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'browser_fingerprint'
	];

	protected $appends = ['picture_info'];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'password' => 'hashed',
			'last_login_at' => 'datetime:Y-m-d H:i:s',
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
			'deleted_at' => 'datetime:Y-m-d H:i:s'
		];
	}

	public function getPictureInfoAttribute(): object|null
	{
		return UploadRecord::query()
			->whereNull('deleted_at')
			->select('url', 'origin_name', 'suffix')
			->find($this->attributes['picture']);
	}
}
