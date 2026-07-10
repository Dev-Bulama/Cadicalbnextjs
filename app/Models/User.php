<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name', 'email', 'password', 'role', 'image', 'premium', 'bio', 'phone',
    'address', 'city', 'state', 'zip_code', 'country', 'banned', 'ban_reason',
    'ban_expires', 'google_id',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    // Role strings match the original app's convention exactly (lowercase, free-form on User.role).
    public const ROLE_SUPER_ADMIN = 'superadmin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_CLINICIAN = 'clinician';

    public const ROLE_SUPPLIER = 'supplier';

    public const ROLE_VENDOR = 'vendor';

    public const ROLE_TECHNICIAN = 'technician';

    public const ROLE_CUSTOMER = 'customer';

    public const ROLE_HOSPITAL = 'hospital';

    public const ROLE_FREE_USER = 'user';

    public const ROLE_REDIRECTS = [
        self::ROLE_SUPER_ADMIN => '/admin/dashboard',
        self::ROLE_ADMIN => '/admin/dashboard',
        self::ROLE_TECHNICIAN => '/technician/jobs',
        self::ROLE_CLINICIAN => '/clinician/dashboard',
        self::ROLE_SUPPLIER => '/supplier/dashboard',
        self::ROLE_VENDOR => '/supplier/dashboard',
        self::ROLE_HOSPITAL => '/products',
        self::ROLE_CUSTOMER => '/products',
        self::ROLE_FREE_USER => '/products',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'premium' => 'boolean',
            'banned' => 'boolean',
            'ban_expires' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN], true);
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function redirectPath(): string
    {
        return self::ROLE_REDIRECTS[$this->role] ?? '/products';
    }

    public function clinician(): HasOne
    {
        return $this->hasOne(Clinician::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function rfqs(): HasMany
    {
        return $this->hasMany(Rfq::class);
    }

    public function bulkOrders(): HasMany
    {
        return $this->hasMany(BulkOrder::class);
    }

    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function technicianProfile(): HasOne
    {
        return $this->hasOne(TechnicianProfile::class);
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function equipmentRecords(): HasMany
    {
        return $this->hasMany(EquipmentRecord::class);
    }

    public function serviceContracts(): HasMany
    {
        return $this->hasMany(ServiceContract::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }
}
