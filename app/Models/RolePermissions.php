<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
	use HasFactory;

	protected $table = 'role_permissions';
	protected $primaryKey = 'role_id';
	public $timestamps = false;
	protected $fillable = [
		'role_id',
		'permission_ids'
	];

	protected function casts(): array
	{
		return ['permission_ids' => Json::class];
	}
}
