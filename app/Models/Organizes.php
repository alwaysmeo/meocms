<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organizes extends Model
{
    use HasFactory;

    protected $table = 'organizes';

    protected $fillable = [
        'name',
        'description',
        'slot',
	    'show'
    ];

	public function getShowAttribute($value): bool
	{
		return [0 => false, 1 => true][$value];
	}
}
