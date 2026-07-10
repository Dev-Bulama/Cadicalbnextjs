<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['institution_id', 'name', 'type', 'url', 'status'])]
class Document extends Model
{
    public const UPDATED_AT = null;

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
