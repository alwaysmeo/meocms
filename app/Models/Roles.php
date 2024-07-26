<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Roles extends Model
{
	use HasFactory;

	protected $table = 'roles';
	protected $fillable = [
		'name',
		'description',
		'show',
		'order'
	];
	protected $appends = ['count', 'permission_ids'];
	protected $hidden = ['laravel_through_key'];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
			'deleted_at' => 'datetime:Y-m-d H:i:s'
		];
	}

	public function getCountAttribute(): int
	{
		return UserRole::query()
			->where('role_id', $this->attributes['id'])
			->whereHas('user_info', function ($query) {
				$query->whereNull('deleted_at');
			})
			->whereHas('role_info', function ($query) {
				$query->whereNull('deleted_at');
			})
			->get()->count();
	}

	public function getShowAttribute($value): bool
	{
		return [0 => false, 1 => true][$value];
	}

	public function getPermissionIdsAttribute(): array
	{
		$res = RolePermissions::query()->find($this->attributes['id']);
		return isset($res['permission_ids']) ? json_decode($res['permission_ids']) : [];
	}

	public function organize_info(): HasOneThrough
	{
		return $this->HasOneThrough(Organizes::class, RoleOrganize::class, 'role_id', 'id', 'id', 'organize_id');
	}

	public function user_info(): hasManyThrough
	{
		return $this->hasManyThrough(Users::class, UserRole::class, 'role_id', 'ulid', 'id', 'user_ulid');
	}
}
