<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\DataTables\OrdersDataTable;
use App\Charts\CustomerChart;
use App\Charts\MonthlySales;
use App\Charts\YearlySales;      // Chart for yearly sales (adjust its class if needed)
use App\Charts\SalesBarChart;    // Chart for sales by date (bar chart with date range)
use App\Charts\ItemChart;        // Doughnut chart for product sales distribution
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $bgcolor;
    
    public function __construct()
    {
        // Define a collection of background colors to be used in the charts.
        $this->bgcolor = collect([
            '#7158e2',
            '#3ae374',
            '#ff3838',
            "#FF851B",
            "#7FDBFF",
            "#B10DC9",
            "#FFDC00",
            "#001f3f",
            "#39CCCC",
            "#01FF70",
            "#85144b",
            "#F012BE",
            "#3D9970",
            "#111111",
            "#AAAAAA",
        ]);
    }
    
    public function index(Request $request)
    {
        // --- Customer Demographics Chart ---
        $customerData = DB::table('customer')
            ->whereNotNull('addressline')
            ->groupBy('addressline')
            ->pluck(DB::raw('count(addressline) as total'), 'addressline')
            ->all();

        $customerChart = new CustomerChart;
        $customerChart->labels(array_keys($customerData));
        $customerChart->dataset('Customer Demographics', 'bar', array_values($customerData))
                      ->backgroundColor($this->bgcolor);
        $customerChart->options([
            'indexAxis' => 'y',
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);

        // --- Monthly Sales Chart ---
        $monthlySalesData = DB::table('orderinfo AS o')
            ->join('orderline AS ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id')
            ->groupBy(DB::raw('month(o.date_placed)'))
            ->orderBy(DB::raw('month(o.date_placed)'), 'ASC')
            ->pluck(DB::raw('sum(ol.quantity * i.sell_price) as total'), DB::raw('monthname(o.date_placed) as month'))
            ->all();

        $monthlySalesChart = new MonthlySales;
        $monthlySalesChart->labels(array_keys($monthlySalesData));
        $monthlySalesChart->dataset('Monthly Sales 2025', 'line', array_values($monthlySalesData))
                          ->backgroundColor($this->bgcolor);
        $monthlySalesChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);

        // --- Yearly Sales Chart ---
        // The YearlySales chart class should be adjusted if needed (e.g. remove unsupported methods such as borderColor).
        $yearlySalesChart = new YearlySales;

        // --- Sales Bar Chart with Date Range ---
        // Get date range inputs from request.
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        // Build the query with optional date range filtering.
        $salesBarQuery = DB::table('orderinfo AS o')
            ->join('orderline AS ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id');

        if ($dateFrom && $dateTo) {
            // Ensure the starting date is not greater than the ending date.
            if (strtotime($dateFrom) > strtotime($dateTo)) {
                // Swap the dates if necessary.
                $temp = $dateFrom;
                $dateFrom = $dateTo;
                $dateTo = $temp;
            }
            $salesBarQuery->whereBetween('o.date_placed', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $salesBarQuery->where('o.date_placed', '>=', $dateFrom);
        } elseif ($dateTo) {
            $salesBarQuery->where('o.date_placed', '<=', $dateTo);
        }

        $salesBarData = $salesBarQuery->groupBy(DB::raw('DATE(o.date_placed)'))
            ->orderBy(DB::raw('DATE(o.date_placed)'), 'ASC')
            ->pluck(DB::raw('sum(ol.quantity * i.sell_price) as total'), DB::raw('DATE(o.date_placed) as date'))
            ->all();

        ksort($salesBarData);
        $salesBarLabels = array_keys($salesBarData);
        $salesBarTotals = array_values($salesBarData);

        $salesBarChart = new SalesBarChart;
        $salesBarChart->labels($salesBarLabels);
        $salesBarChart->dataset('Sales by Date', 'bar', $salesBarTotals)
                      ->backgroundColor($this->bgcolor->toArray());
        $salesBarChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);

        // --- Product Sales Distribution Chart (Pie/Doughnut Chart) ---
        $itemSalesData = DB::table('orderline AS ol')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id')
            ->groupBy('i.description')
            ->orderBy(DB::raw('sum(ol.quantity)'), 'DESC')
            ->pluck(DB::raw('sum(ol.quantity) as total'), 'description')
            ->all();

        $itemChart = new ItemChart;
        $itemChart->labels(array_keys($itemSalesData));
        $itemChart->dataset('Product Sales Distribution', 'doughnut', array_values($itemSalesData))
                  ->backgroundColor($this->bgcolor)
                  ->fill(false);
        $itemChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
        ]);

        return view('dashboard.index', compact(
            'customerChart',
            'monthlySalesChart',
            'yearlySalesChart',
            'salesBarChart',
            'itemChart'
        ));
    }

    public function getUsers(UsersDataTable $dataTable)
    {
        return $dataTable->render('dashboard.users');
    }

    public function getOrders(OrdersDataTable $dataTable)
    {
        return $dataTable->render('dashboard.orders');
    }
}
