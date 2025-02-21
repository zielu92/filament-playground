<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AttendeeResource;
use Filament\Widgets\Widget;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Modal\Actions\Action;
use Illuminate\Support\Facades\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class TestChart extends Widget implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected int|string|array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.test-chart';

    public function callNotification(): Action
    {
        return Action::make("call notifcation")
        ->button()
        ->color("warning")
        ->label("send a notification")
        ->action( function() {
            Notification::make()->warning()->title("test notifcation")
            ->body("this is a test")
            ->persistent()
            ->actioctions([
                \Filament\Notifications\Actions\Action::make('toToAtendees')
                ->button()
                ->color("primary")
                ->url(AttendeeResource::getUrl('edit', ['record'=>1])),
                \Filament\Notifications\Actions\Action::make('undo')
                ->link()
                ->color("grey")
                ->action(function() {
                    //
                })
            ])
            ->send();
        });
    }
}
