<?php

namespace gift\appli\models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'libelle',
        'description',
    ];

    public function prestations()
    {
        return $this->hasMany(Prestation::class, 'cat_id', 'id');
    }
}