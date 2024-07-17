<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
		'picture_id',
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

	protected $appends = ['picture_info'];

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

	public function getPictureInfoAttribute(): object|null
	{
		if (!isset($this->attributes['picture_id'])) return null;
		return UploadRecord::query()
			->whereNull('deleted_at')
			->select('url', 'origin_name', 'suffix')
			->find($this->attributes['picture_id']);
	}

	public function organize_info(): HasOneThrough
	{
		return $this->HasOneThrough(Organizes::class, UserOrganize::class, 'user_ulid', 'id', 'ulid', 'organize_id');
	}

	public function role_info(): HasOneThrough
	{
		return $this->HasOneThrough(Roles::class, UserRole::class, 'user_ulid', 'id', 'ulid', 'role_id');
	}
}
