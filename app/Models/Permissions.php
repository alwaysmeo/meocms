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
}
