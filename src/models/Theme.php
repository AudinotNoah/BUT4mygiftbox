<?php

namespace gift\appli\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $table = 'theme';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['libelle', 'description'];

    public function coffretTypes(): HasMany
    {
        return $this->hasMany(CoffretType::class, 'theme_id', 'id');
    }
}