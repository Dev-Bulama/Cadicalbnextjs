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

    /**
     * Resolve a stored image reference into a renderable URL. Handles three
     * shapes: a full external URL (hotlinked, e.g. a partner logo), an
     * uploaded file under storage/app/public, or a bundled public/ asset.
     */
    public static function mediaUrl(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return str_starts_with($path, '/storage') ? url($path) : asset($path);
    }
}
