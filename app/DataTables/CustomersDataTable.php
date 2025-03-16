<?php

namespace App\DataTables;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;


class CustomersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return EloquentDataTable
     */
    public function dataTable($query)
    {
        return datatables()->eloquent($query)
            ->addColumn('full_name', function ($row) {
                return $row->fname . ' ' . $row->lname;
            })
            ->rawColumns(['full_name'])
            ->setRowId('customer_id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @param  \App\Models\Customer  $model
     * @return QueryBuilder
     */
    public function query(Customer $model): QueryBuilder
    {
        // Join the customers table with the users table to fetch the customer's email.
        return $model->newQuery()
            ->join('users', 'customer.user_id', '=', 'users.id')
            ->select(
                'customer.customer_id',
                'customer.title',
                'customer.fname',
                'customer.lname',
                'customer.addressline',
                'customer.town',
                'customer.zipcode',
                'customer.phone',
                'users.email',
                'customer.created_at'
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
            ->setTableId('customers-table')
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
            Column::make('customer_id')->title('Customer ID'),
            Column::computed('full_name')->title('Full Name'),
            Column::make('email')->title('Email'),
            Column::make('addressline')->title('Address')->searchable(false),
            Column::make('town')->title('Town'),
            Column::make('zipcode')->title('Zipcode'),
            Column::make('phone')->title('Phone'),
            Column::make('created_at')->title('Created At'),
        ];
    }

    /**
     * Get the filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Customers_' . date('YmdHis');
    }
}
