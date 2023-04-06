<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsignanteMaster extends Model
{
    use HasFactory;


    protected $fillable = [
        'name'
    ];

    public function consignantes(): HasMany
    {
        return $this->hasMany(Consignante::class);
    }
}
