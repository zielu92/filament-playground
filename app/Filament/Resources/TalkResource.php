<?php

namespace App\Filament\Resources;

use App\Models\Talk;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Illuminate\Database\Schema\Blueprint;
use App\Filament\Resources\TalkResource\Pages;


class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Second group';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Talk::getForm()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function($action) {
                return $action->button()->label('Filters');
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description( function(Talk $record) {
                        return Str::of($record->abstract, 40);
                    }),
                Tables\Columns\ImageColumn::make('speaker.avatar')
                ->label('speaker avatar')
                ->circular()
                ->defaultImageUrl(function ($record){
                    return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='.urlencode($record->speaker->name);
                }),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('new_talk'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(function ($state){
                        return $state->getColor();
                    }),
                Tables\Columns\IconColumn::make('length')
                    ->icon(function ($state) {
                        return match($state) {
                            TalkLength::NORMAL => 'heroicon-o-megaphone',
                            TalkLength::LIGHTNING => 'heroicon-o-bolt',
                            TalkLength::KEYNOTE => 'heroicon-o-star'
                        };
                    }),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('new_talk'),
                Tables\Filters\SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Tables\Filters\Filter::make('has_avatar')
                    ->toggle()
                    ->label('show only with avatars')
                    ->query(function($query) {
                        return $query->whereHas('speaker', function (Blueprint $query){
                            $query->whereNotNull('avatar');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                    ->visible( function ($record){
                        return $record->status == TalkStatus::SUBMITTED;
                    })
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Talk $record) {
                        $record->approve();
                    })->after( function() {
                        Notification::make()->success()
                        ->duration(1000)
                        ->title('Talk has been approved')
                        ->body('something')
                        ->send();
                    }),
                    Tables\Actions\Action::make('reject')
                    ->color('danger')
                    ->icon('heroicon-o-no-symbol')
                    ->visible( function ($record){
                        return $record->status == TalkStatus::SUBMITTED;
                    })
                    ->requiresConfirmation()
                    ->action(function (Talk $record) {
                        $record->reject();
                    })->after( function() {
                        Notification::make()->danger()
                        ->duration(1000)
                        ->title('Talk has been rejected')
                        ->body('something')
                        ->send();
                    }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                    ->action( function(Collection $records){
                        $records->each->approve();
                    }),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                ->tooltip('export records test')
                ->action(function ($livewire) {

                })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            // 'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
