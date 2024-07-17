<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $table = 'permissions';
	public $timestamps = false;
    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'description',
        'icon',
        'path',
        'slot',
        'level',
        'show'
    ];

	public function getShowAttribute($value): bool
	{
		return [0 => false, 1 => true][$value];
	}
}
