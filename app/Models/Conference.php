<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Get;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'region',
        'venue_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
        'region' => Region::class
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public static function getForm(): array
    {
        return [
            //can do wizzard...
            Section::make('Conferece Details')
            ->collapsible()
            ->description("something here")
            ->columns(['md' => 2, 'lg' => 3])
            ->schema([
                Shout::make('warn-price')
                ->type('warning')
                ->columnSpanFull()
                ->visible(function(Get $get){
                    return $get('ticket_costs') > 500;
                }),
                TextInput::make('name')
                ->columnSpanFull()
                ->label("Conferene name")
                ->required()
                ->maxLength(60),
                MarkdownEditor::make('description')
                ->columnSpanFull()
                ->required(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                    Fieldset::make('Status')
                    ->columns(2)
                    ->schema([
                Toggle::make('is_published')
                ->default(true),
                TextInput::make('ticket_costs')
                ->label("Ticket Cost")
                ->lazy()
                ->required()
                ->maxLength(60),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ])
                    ->required(),
                ]),
                Select::make('region')
                ->live()
                ->options(Region::class),
                Select::make('venue_id')
                    ->label("Venue")
                    ->live()
                    ->options(function(Get $get) {
                        return Venue::where('region', $get('region'))->pluck('name', 'id');
                    })

            ]),
            Actions::make([
                Action::make('star')
                ->label('fill with factory data')
                ->icon('heroicon-m-star')
                ->visible(function(string $operation) {
                    if($operation !== 'create') {
                        return false;
                    }
                    if(!app()->environment('local')) {
                        return false;
                    }
                    return true;
                })
                ->action(function ($livewire) {
                    $data = Conference::factory()->make()->toArray();
                    $livewire->form->fill($data);
                }),
            ]),
            Tabs::make()
            ->columnSpanFull()
            ->tabs([
                Tabs\Tab::make("Speakers")
                ->schema([
                    CheckboxList::make('speakers')
                    ->relationship('speakers', 'name')
                    ->options(
                        Speaker::pluck('name', 'id')
                    )
                    ->required()
                ])
            ]),

        ];
    }
}
