<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'client_id',
        'due_date',
        'payment_date',
        'installment',
        'price',
        'status',
    ];

    public function rental(){
        return $this->belongsTo(Rental::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    
}
