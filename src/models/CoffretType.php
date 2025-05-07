<?php

namespace gift\appli\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CoffretType extends Model
{
    protected $table = 'coffret_type';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['libelle', 'description', 'theme_id'];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }

    public function prestations(): BelongsToMany
    {
        return $this->belongsToMany(
            Prestation::class,
            'coffret2presta',
            'coffret_id', 
            'presta_id',
            'id',
            'id'
        );
    }
}