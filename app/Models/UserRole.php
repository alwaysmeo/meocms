<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class UserRole extends Model
{
	use HasFactory;

	protected $table = 'user_role';
	protected $primaryKey = 'user_ulid';
	public $incrementing = false;
	public $timestamps = false;
	protected $hidden = ['user_ulid'];
	protected $appends = ['name'];
	protected $fillable = [
		'user_ulid',
		'role_id'
	];

	public function getNameAttribute(): string
	{
		return Roles::query()->find($this->attributes['role_id'])['name'];
	}

	public function user_info(): hasOne
	{
		return $this->hasOne(Users::class, 'ulid', 'user_ulid');
	}

	public function role_info(): hasOne
	{
		return $this->hasOne(Roles::class, 'id', 'role_id');
	}

	public function organize_info(): hasOne
	{
		return $this->hasOne(RoleOrganize::class, 'role_id', 'role_id');
	}
}
