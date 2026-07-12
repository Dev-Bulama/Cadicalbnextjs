<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['section_key', 'content'])]
class HomeSection extends Model
{
    protected function casts(): array
    {
        return ['content' => 'array'];
    }

    /**
     * Fetch a section's stored content, falling back to a caller-supplied
     * default when no row exists yet (defensive — the seeder always creates
     * all 13 rows, but this keeps the homepage rendering safe regardless).
     */
    public static function content(string $key, array $default = []): array
    {
        return static::where('section_key', $key)->value('content') ?? $default;
    }
}
