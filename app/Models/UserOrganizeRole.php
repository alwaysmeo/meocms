<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RoleUserOrganize extends Model
{
	use HasFactory;

	protected $table = 'role_user';
	protected $primaryKey = 'user_ulid';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'user_ulid',
		'role_id',
		'organize_id'
	];

	public function user_info(): HasOne
	{
		return $this->hasOne(Users::class, 'ulid', 'user_ulid')
			->select('ulid', 'nickname', 'email', 'phone');
	}

	public function role_info(): HasOne
	{
		return $this->hasOne(Roles::class, 'id', 'role_id');
	}

	public function organize_info(): HasOne
	{
		return $this->hasOne(Organizes::class, 'ulid', 'user_ulid');
	}
}
