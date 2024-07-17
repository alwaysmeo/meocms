<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
	use HasFactory;

	protected $table = 'roles';
	public $timestamps = false;
	protected $fillable = [
		'organize_id',
		'name',
		'slot',
		'show'
	];
	protected $appends = ['count'];

	public function getCountAttribute(): int
	{
		return UserRole::query()->where('role_id', $this->attributes['id'])->get()->count();
	}

	public function getShowAttribute($value): bool
	{
		return [0 => false, 1 => true][$value];
	}
}
