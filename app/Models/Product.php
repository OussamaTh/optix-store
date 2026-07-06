<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'gender',
        'category',
    ];

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants');
    }



    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/placeholder.png');
        }

        // 💡 Changed from Str::starts_with to Str::startsWith
        if (\Illuminate\Support\Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        if (config('app.env') === 'production') {
            return "https://atstkhkmxhxvqxhdzgnx.supabase.co/storage/v1/object/public/products-images/{$this->image_path}";
        }

        return asset('storage/' . $this->image_path);
    }


    // In Product model
    public function priceWithVariant(Variant $v): float
    {
        return $this->price + $v->price;
    }
}
