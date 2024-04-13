<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['size_name'];

    // Mengubah Format Date
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Relation
    public function products()
    {
        return $this->belongsToMany(Product::class, 'size_prod', 'size_id', 'prod_id');
    }
}
