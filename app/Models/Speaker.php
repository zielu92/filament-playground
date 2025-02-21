<?php

namespace App\Models;

use App\Models\Conference;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALFICATIONS = [
        'bussiness-leader' => 'Bussiness Leader',
        'stefan-pazdzioch' => 'Stefan Pazdzioch',
        'cos-tam' => 'Cos tam'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'bio',
        'twitter_handle',
        'conference_id',
        'qualifications'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'conference_id' => 'integer',
        'qualifications' => 'array',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('avatar')
                ->avatar()
                ->image()
                ->directory('avatars')
                ->imageEditor()
                ->maxSize(1024*1024*10),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                RichEditor::make('bio')
                    ->columnSpanFull(),
                TextInput::make('twitter_handle')
                    ->maxLength(255),
                CheckboxList::make('qualifcations')
                    ->columnSpanFull()
                    ->searchable()
                    ->bulkToggleable()
                    ->options(self::QUALFICATIONS)
                    ->descriptions([
                        'bussiness-leader' => 'dsdsd Leader',
                        'stefan-pazdzioch' => 'dsds Pazdzioch',
                        'cos-tam' => 'Cos dsds'
                    ])
                    ->columns(3)
        ];
    }
}
