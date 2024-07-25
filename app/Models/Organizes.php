<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Organizes extends Model
{
    use HasFactory;

    protected $table = 'organizes';
    protected $fillable = [
        'name',
        'description',
	    'show',
        'order'
    ];
	protected $hidden = ['laravel_through_key'];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
			'deleted_at' => 'datetime:Y-m-d H:i:s'
		];
	}

	public function getShowAttribute($value): bool
	{
		return [0 => false, 1 => true][$value];
	}

	public function role_info(): HasOneThrough
	{
		return $this->HasOneThrough(Roles::class, RoleOrganize::class, 'role_id', 'id', 'id', 'organize_id');
	}
}
