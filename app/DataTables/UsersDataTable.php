<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Auth;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()->eloquent($query)
            ->addColumn('role', function ($row) {
                // This view now has access to both $row->role and $row->status if needed.
                return view('users.role', compact('row'));
            })
            ->addColumn('status', function ($row) {
                // Render the status dropdown (status.blade.php)
                return view('users.status', compact('row'));
            })
            ->rawColumns(['role', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        $users = User::join('customer', 'users.id', '=', 'customer.user_id')
            ->select(
                'users.id AS id',
                'users.name',
                'users.email',
                'users.role',      // role column from users table
                'users.status',    // status column from users table
                'customer.addressline',
                'customer.phone',
                'users.created_at'
            )
            ->where('users.id', '<>', Auth::id());

        return $users;
    }

    /**
     * Optional method if you want to use the HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'dom'     => 'Bfrtip',
                'buttons' => ['pdf', 'excel', 'csv', 'reload'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Customer ID'),
            Column::make('name'),
            Column::make('email'),
            Column::make('addressline')->title('Address')->searchable(false),
            Column::make('phone')->searchable(false),
            Column::make('created_at'),
            Column::computed('role')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
            Column::computed('status')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}