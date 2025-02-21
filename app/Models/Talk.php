<?php

namespace App\Models;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'abstrac',
        'speaker_id',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
        'status' => TalkStatus::class,
        'length' => TalkLength::class,
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function approve(): void
    {
        $this->status = TalkStatus::APPROVED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = TalkStatus::REJECTED;
        $this->save();
    }

    public static function getForm($speakerId = null): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            RichEditor::make('abstrac')
                ->required()
                ->columnSpanFull(),
            Select::make('speaker_id')
                ->hidden(function() use ($speakerId) {
                    return $speakerId !== null;
                })
                ->relationship('speaker', 'name')
                ->required()
            ];
    }
}
