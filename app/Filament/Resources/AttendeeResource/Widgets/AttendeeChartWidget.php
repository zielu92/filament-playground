<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use App\Models\Attendee;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class AttendeeChartWidget extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Attendee Signups';

    protected  int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = "200px";
    protected static ?string $pollingInterval = '1s';

    public ?string $filter = '3months';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last week',
            'month' => 'Last month',
            '3months' => 'Last 3 months'
        ];
    }

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        match ($filter) {
            'week' => $data = Trend::query($this->getPageTableQuery())
                ->between(
                    start: now()->subWeek(),
                    end: now(),
                )
                ->perDay()
                ->count(),
            'month' => $data = Trend::query($this->getPageTableQuery())
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count(),
            '3months' =>  $data = Trend::query($this->getPageTableQuery())
            ->between(
                start: now()->subMonths(3),
                end: now(),
            )
            ->perMonth()
            ->count()
        };

    return [
        'datasets' => [
            [
                'label' => 'Signups',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
