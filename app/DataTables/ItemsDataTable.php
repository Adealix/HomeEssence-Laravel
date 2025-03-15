<?php

namespace App\DataTables;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Storage;

class ItemsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('images', function ($row) {
                // Check if productImages relationship is loaded and has images.
                if ($row->productImages->isNotEmpty()) {
                    $carouselId = 'carousel-' . $row->item_id;
                    $html = '<div id="'.$carouselId.'" class="carousel slide" data-ride="carousel" data-interval="false">';
                    $html .= '<div class="carousel-inner">';
                    foreach ($row->productImages as $index => $image) {
                        $active = $index === 0 ? ' active' : '';
                        $imgUrl = Storage::url($image->image_path);
                        $html .= '<div class="carousel-item'.$active.'">';
                        $html .= '<img src="'.$imgUrl.'" width="50" height="50" alt="Item Image">';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                    // Carousel controls
                    $html .= '<a class="carousel-control-prev" href="#'.$carouselId.'" role="button" data-slide="prev">';
                    $html .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    $html .= '<span class="sr-only">Previous</span>';
                    $html .= '</a>';
                    $html .= '<a class="carousel-control-next" href="#'.$carouselId.'" role="button" data-slide="next">';
                    $html .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    $html .= '<span class="sr-only">Next</span>';
                    $html .= '</a>';
                    $html .= '</div>';
                    return $html;
                }
                // Fallback if no images exist
                return '<img src="/images/default.png" width="50" height="50" alt="No image available">';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('items.edit', $row->item_id);
                $deleteUrl = route('items.destroy', $row->item_id);
                $btn  = '<a href="'.$editUrl.'" class="btn btn-sm btn-primary">Edit</a> ';
                $btn .= '<form action="'.$deleteUrl.'" method="POST" style="display:inline;">'
                        . csrf_field()
                        . method_field('DELETE')
                        . '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>'
                        . '</form>';
                return $btn;
            })
            ->rawColumns(['images', 'action'])
            ->setRowId('item_id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param Item $model
     * @return QueryBuilder
     */
    public function query(Item $model): QueryBuilder
    {
        // Eager load productImages; note: the join for stock is preserved.
        return $model->newQuery()
            ->with('productImages')
            ->join('stock', 'item.item_id', '=', 'stock.item_id')
            ->select(
                'item.item_id',
                'item.name', // Added 'name' column
                'item.description',
                'item.category', // Added 'category' column
                'item.cost_price',
                'item.sell_price',
                'stock.quantity as quantity'
            );
    }

    /**
     * Optional method if you want to use the HTML builder.
     *
     * @return HtmlBuilder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('items-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('item_id')->title('ID'),
            Column::make('name')->title('Name'), // Added 'name' column
            Column::make('images')->title('Images'),
            Column::make('description')->title('Description'),
            Column::make('category')->title('Category'), // Added 'category' column
            Column::make('cost_price')->title('Cost Price'),
            Column::make('sell_price')->title('Sell Price'),
            Column::make('quantity')->title('Stock Quantity'),
        ];
    }

    /**
     * Get the filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Items_' . date('YmdHis');
    }
}
