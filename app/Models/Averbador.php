<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Averbador extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'consignante_id'
    ];

    public function consignante(): BelongsTo
    {
        return $this->belongsTo(Consignante::class);
    }
}
