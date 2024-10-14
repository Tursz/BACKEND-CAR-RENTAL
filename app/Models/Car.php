<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'color_id',
        'type_id',
        'name',
        'plate',
        'km',
        'chassi',
        'is_available',
        'price',
        'year',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
