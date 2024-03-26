<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $table = 'cards';
    protected $fillable = [
        'user_id',
        'logo',
        'title',
        'slogan',
        'phonenumber',
        'email',
        'address',
        'website',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
