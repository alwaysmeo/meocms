<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Roles extends Model
{
	use HasFactory;

	protected $table = 'roles';
	protected $fillable = [
		'name',
		'description',
		'slot',
		'show'
	];
	protected $appends = ['count'];
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
		return UserRole::query()->where('role_id', $this->attributes['id'])->get()->count();
	}

	public function getShowAttribute($value): bool
	{
		return [0 => false, 1 => true][$value];
	}

	public function organize_info(): HasOneThrough
	{
		return $this->HasOneThrough(Organizes::class, RoleOrganize::class, 'role_id', 'id', 'id', 'organize_id');
	}
}
