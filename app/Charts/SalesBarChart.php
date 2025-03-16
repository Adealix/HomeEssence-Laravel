<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class SalesBarChart extends Chart
{
    public function __construct()
    {
        parent::__construct();
        // Set default empty labels and dataset (will be populated in the controller)
        $this->labels([]);
        $this->dataset('Sales', 'bar', [])
             ->backgroundColor([]);
        $this->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);
    }
}
