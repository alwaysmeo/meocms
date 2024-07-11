<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RoleUser extends Model
{
	use HasFactory;

	protected $table = 'role_user';
	protected $primaryKey = 'user_ulid';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'user_ulid',
		'role_id'
	];

	public function user_info(): HasOne
	{
		return $this->hasOne(Users::class, 'ulid', 'user_ulid')
			->select('ulid', 'nickname', 'email', 'phone');
	}
}
