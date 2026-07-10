<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'category', 'icon', 'is_active', 'order'])]
class Service extends Model
{
    public const CATEGORY_CONSULTATIONS = 'CONSULTATIONS';

    public const CATEGORY_PHARMACEUTICALS = 'PHARMACEUTICALS';

    public const CATEGORY_SURGICAL_EQUIPMENT = 'SURGICAL_EQUIPMENT';

    public const CATEGORY_DIAGNOSTICS = 'DIAGNOSTICS';

    public const CATEGORY_REHABILITATION = 'REHABILITATION';

    public const CATEGORY_EMERGENCY = 'EMERGENCY';

    public const CATEGORY_COSMETICS = 'COSMETICS';

    public const CATEGORY_REFERRALS = 'REFERRALS';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
