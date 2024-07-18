<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleOrganize extends Model
{
	use HasFactory;

	protected $table = 'role_organize';
	protected $primaryKey = 'role_id';
	public $incrementing = false;
	public $timestamps = false;
	protected $fillable = [
		'role_id',
		'organize_id'
	];
}
