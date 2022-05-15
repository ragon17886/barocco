<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @package App
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic_name
 * @property-read Cart $cart
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usergroup_id',
        'first_name',
        'last_name',
        'patronymic_name',
        'phone',
        'email',
        'birth_date',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User's cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_token');
    }

    /**
     * User addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * Get fisrt user address if exist
     *
     * @return \App\Models\UserAddress
     */
    public function getFirstAddress()
    {
        return optional($this->addresses[0] ?? null);
    }

    /**
     * Get fisrt user address country id if exist
     */
    public function getFirstAddressCountryId(): ?int
    {
        return $this->getFirstAddress()->country_id;
    }

    /**
     * Check user has addresses
     */
    public function hasAddresses(): bool
    {
        return !empty($this->getFirstAddress()->id);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return "{$this->last_name} {$this->first_name} {$this->patronymic_name}";
    }

    /**
     * Interact with the user's first name.
     *
     * @param string $firstName
     */
    public function firstName(): Attribute
    {
        return Attribute::make(
            get: fn ($firstName) => Str::ucfirst($firstName)
        );
    }

    /**
     * Farmat date in admin panel
     *
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d.m.Y H:i:s');
    }
}
