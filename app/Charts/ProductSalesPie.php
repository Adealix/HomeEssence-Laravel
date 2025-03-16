<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use DB;
use Illuminate\Support\Facades\Request;

class ProductSalesPieChart extends Chart
{
    public function __construct()
    {
        parent::__construct();

        // Retrieve date range from request parameters if provided.
        $dateFrom = Request::get('date_from');
        $dateTo   = Request::get('date_to');

        // Build the query with an optional date range filter.
        $query = DB::table('orderline AS ol')
            ->join('orderinfo AS o', 'ol.orderinfo_id', '=', 'o.orderinfo_id')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id');

        if ($dateFrom) {
            $query->whereDate('o.date_placed', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('o.date_placed', '<=', $dateTo);
        }

        $productSales = $query->groupBy('i.name')
            ->orderBy('total', 'DESC')
            ->pluck(DB::raw('sum(ol.quantity * i.sell_price) AS total'), 'i.name')
            ->toArray();

        // Set the labels and dataset for the pie chart.
        $this->labels(array_keys($productSales));
        $this->dataset('Sales by Product', 'pie', array_values($productSales))
             ->backgroundColor([
                '#7158e2',
                '#3ae374',
                '#ff3838',
                '#FF851B',
                '#7FDBFF',
                '#B10DC9',
                '#FFDC00',
                '#001f3f',
                '#39CCCC',
                '#01FF70',
                '#85144b',
                '#F012BE',
                '#3D9970',
                '#111111',
                '#AAAAAA',
             ]);

        $this->options([
            'responsive' => true,
            'legend' => ['position' => 'bottom'],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);
    }
}
