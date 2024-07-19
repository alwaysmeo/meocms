<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as AuthenticatableAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends AuthenticatableAuthenticatable
{
	use HasApiTokens, HasUlids, HasFactory, Notifiable;

	protected $table = 'users';
	protected $primaryKey = 'ulid';
	protected $rememberTokenName = 'token';

	/**
	 * The attributes that are mass assignable.
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
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password'
	];

	/**
	 * Get the attributes that should be cast.
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

	public function getPictureAttribute(): object|null
	{
		if (!isset($this->attributes['picture'])) return null;
		return UploadRecord::query()
			->whereNull('deleted_at')
			->select('url', 'origin_name', 'suffix')
			->find($this->attributes['picture']);
	}

	public function organize_info(): HasManyThrough
	{
		return $this->HasManyThrough(Organizes::class, UserOrganize::class, 'user_ulid', 'id', 'ulid', 'organize_id');
	}

	public function role_info(): HasOneThrough
	{
		return $this->HasOneThrough(Roles::class, UserRole::class, 'user_ulid', 'id', 'ulid', 'role_id');
	}
}
