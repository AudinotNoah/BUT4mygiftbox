<?php

declare(strict_types=1);

namespace gift\core\domain\entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'user';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'password',
        'role'
    ];

    public function boxes(): HasMany
    {
        return $this->hasMany(Box::class, 'createur_id', 'id');
    }
}