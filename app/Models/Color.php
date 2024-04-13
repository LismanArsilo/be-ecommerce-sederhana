<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['color_name'];

    // Mengubah Format Date
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Relations
    public function products()
    {
        return $this->belongsToMany(Product::class, 'color_prods', 'color_id', 'prod_id');
    }
}
