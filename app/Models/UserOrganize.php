<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganize extends Model
{
	use HasFactory;

	protected $table = 'user_organize';
	protected $primaryKey = 'user_ulid';
	public $incrementing = false;
	public $timestamps = false;
	protected $hidden = ['user_ulid'];
	protected $appends = ['name'];
	protected $fillable = [
		'user_ulid',
		'organize_id'
	];

	public function getNameAttribute(): string
	{
		return Organizes::query()->find($this->attributes['organize_id'])['name'];
	}
}
