<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use DB;

class YearlySales extends Chart
{
    public function __construct()
    {
        parent::__construct();

        // Get distinct years from orders (assuming date_placed is stored in orderinfo)
        $years = DB::table('orderinfo')
            ->select(DB::raw('YEAR(date_placed) as year'))
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->pluck('year')
            ->toArray();

        // Get total sales per year
        $salesData = DB::table('orderinfo AS o')
            ->join('orderline AS ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id')
            ->select(DB::raw('YEAR(o.date_placed) as year, sum(ol.quantity * i.sell_price) as total'))
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->pluck('total', 'year')
            ->toArray();

        $data = [];
        foreach ($years as $year) {
            $data[] = isset($salesData[$year]) ? $salesData[$year] : 0;
        }

        // Set chart labels and dataset (without using borderColor() on dataset)
        $this->labels($years);
        $this->dataset('Yearly Sales', 'line', $data)
             ->backgroundColor('rgba(54, 162, 235, 0.2)')
             ->fill(true);

        // Set additional options including the border color for the line via options
        $this->options([
            'elements' => [
                'line' => [
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);
    }
}
