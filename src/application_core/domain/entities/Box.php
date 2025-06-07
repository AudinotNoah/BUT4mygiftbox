<?php

declare(strict_types=1);

namespace gift\core\domain\entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Box extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string'; 

    protected $table = 'box';
    protected $primaryKey = 'id'; 


    protected $fillable = [
        'id',
        'token',
        'libelle',
        'description',
        'montant',
        'kdo',
        'message_kdo',
        'statut',
        'createur_id',
    ];


    public $timestamps = true; 


    protected $casts = [
        'kdo' => 'boolean',
        'montant' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function prestations(): BelongsToMany
    {
        return $this->belongsToMany(
            Prestation::class,      
            'box2presta', 
            'box_id',     
            'presta_id', 
            'id',   
            'id'     
        )->withPivot('quantite');   
    }


}