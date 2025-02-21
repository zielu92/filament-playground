<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnttendeesStatsWidgets extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Attendess Count', $this->getPageTableQuery()->count())
            ->description('Total amount of atendees')
            ->color('success')
            ->chart([1,2,3,4,5]),
            Stat::make('total Revenue', $this->getPageTableQuery()->sum('ticket_cost')/100)
        ];
    }
}
