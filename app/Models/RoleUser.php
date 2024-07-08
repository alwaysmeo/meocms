<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Model
{
	use HasFactory;

	protected $table = 'role_user';
	protected $primaryKey = 'user_ulid';
	public $timestamps = false;

	protected $fillable = [
		'user_ulid',
		'role_id'
	];
}
