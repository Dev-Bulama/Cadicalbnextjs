<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'first_name', 'last_name', 'specialization', 'license_number',
    'years_of_experience', 'bio', 'profile_image', 'verified', 'is_available',
])]
class Clinician extends Model
{
    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
