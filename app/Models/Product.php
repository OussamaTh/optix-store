<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        $base = config('filesystems.disks.s3.public_url_base');

        if (empty($base)) {
            // Hardcoded fallback so images never silently break again.
            // TODO: fix env/config caching so this branch is never hit.
            $base = 'https://atstkhkmxhxvqxhdzgnx.supabase.co/storage/v1/object/public/products-images';
        }

        return rtrim($base, '/') . '/' . ltrim($this->image_path, '/');
    }

    public function priceWithVariant(Variant $v): float
    {
        return $this->price + $v->price;
    }
}
