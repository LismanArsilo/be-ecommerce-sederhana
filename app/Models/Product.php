<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'cate_id',
        'name',
        'price',
        'description',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id', 'id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'size_prods', 'prod_id', 'size_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'color_prods', 'prod_id', 'color_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'prod_id', 'id');
    }
}
