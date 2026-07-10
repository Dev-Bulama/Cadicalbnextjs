<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'reference', 'participants', 'is_active'])]
class ChatRoom extends Model
{
    protected function casts(): array
    {
        return [
            'participants' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }
}
