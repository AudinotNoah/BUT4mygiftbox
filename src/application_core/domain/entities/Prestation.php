<?php

namespace gift\core\domain\entities;

use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    protected $table = 'prestation';
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'cat_id', 'id');
    }

    public function coffretTypes()
    {
        return $this->belongsToMany(
            CoffretType::class,
            'coffret2presta',
            'presta_id',
            'coffret_id',
            'id',
            'id'
        );
    }

}