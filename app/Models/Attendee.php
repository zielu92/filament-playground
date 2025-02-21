<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendee extends Model
{
    /** @use HasFactory<\Database\Factories\AttendeeFactory> */
    use HasFactory;

    protected $fillable = ['conference_id', 'ticket_cost', 'name', 'email', 'is_paid'];

    public function conference(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Group::make()->columns(2)->schema([
                TextInput::make('name')
                    ->required()->maxLength(255),
                TextInput::make('email')
                    ->email()->required()->maxLength(255),
            ])
        ];
    }
}
