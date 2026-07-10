<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['connection_id', 'entity', 'cadical_field', 'crm_field', 'direction', 'is_required', 'transform_fn'])]
class CrmFieldMapping extends Model
{
    protected function casts(): array
    {
        return ['is_required' => 'boolean'];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(CrmConnection::class, 'connection_id');
    }
}
