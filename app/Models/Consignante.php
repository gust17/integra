<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consignante extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'consignante_master_id'
    ];



    public function consignante_master(): BelongsTo
    {
        return $this->belongsTo(ConsignanteMaster::class);
    }

    public function averbadors()
    {
        return $this->hasMany(Averbador::class);
    }
}
