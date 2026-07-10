<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'inst_name', 'inst_type', 'cac', 'year_established', 'staff_count', 'bed_capacity',
    'state', 'lga', 'address', 'contact_name', 'designation', 'phone', 'alt_phone', 'email',
    'services', 'specialist_opts', 'consult_opts', 'reagent_opts', 'edu_opts', 'volume',
    'notes', 'nafdac', 'pcn', 'confirm_docs', 'account_email', 'password_hash',
])]
#[Hidden(['password_hash'])]
class Institution extends Model
{
    protected function casts(): array
    {
        return [
            'services' => 'array',
            'specialist_opts' => 'array',
            'consult_opts' => 'array',
            'reagent_opts' => 'array',
            'edu_opts' => 'array',
            'confirm_docs' => 'boolean',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function rfqs(): HasMany
    {
        return $this->hasMany(Rfq::class);
    }

    public function bulkOrders(): HasMany
    {
        return $this->hasMany(BulkOrder::class);
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
}
