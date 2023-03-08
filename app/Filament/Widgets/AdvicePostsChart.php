<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Advice;

class AdvicePostsChart extends LineChartWidget
{
    protected static ?string $heading = 'Advice Posts';

    protected function getData(): array
    {
        // MOCK UP DATA
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];

        // TODO: With real data
        // $data = Trend::model(Advice::class)
        //             ->between(
        //                 start: now()->startOfYear(),
        //                 end: now()->endOfYear(),
        //             )
        //             ->perMonth()
        //             ->count();

        // return [
        //     'datasets' => [
        //         'label' => 'Advice posts',
        //         'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
        //     ],
        //     'labels' => $data->map(fn (TrendValue $value) => $value->date),
        // ];
    }
}
