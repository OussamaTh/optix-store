<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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

        if (\Illuminate\Support\Str::starts_with($this->image_path, 'http://') || \Illuminate\Support\Str::starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (config('app.env') === 'production') {
            // 💡 CHANGE THIS string below to match your real Supabase project sub-domain string
            $projectRef = 'optix-store';
            return "https://{$projectRef}.storage.supabase.co/object/public/product-images/{$this->image_path}";
        }

        return asset('storage/' . $this->image_path);
    }


    // In Product model
    public function priceWithVariant(Variant $v): float
    {
        return $this->price + $v->price;
    }
}
