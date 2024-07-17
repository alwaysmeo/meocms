<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
