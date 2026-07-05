<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function addToCart(Product $product, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $variant = $variantId ? Variant::find($variantId) : null;
        $unitPrice = $variant ? $product->priceWithVariant($variant) : $product->price;

        $item = $this->cartItems()->firstOrNew([
            'product_id' => $product->id,
            'variant_id' => $variantId,
        ]);

        if ($item->exists) {
            $item->quantity += $quantity;
        } else {
            $item->quantity = $quantity;
            $item->unit_price = $unitPrice;
        }

        $item->save();

        return $item;
    }
}
