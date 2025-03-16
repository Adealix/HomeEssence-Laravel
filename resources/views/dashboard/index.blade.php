@extends('layouts.base')
@section('body')
    @include('layouts.flash-messages')
    <div class="container">
        <h2>Dashboard</h2>
        
        <!-- Date Range Filter for Sales Bar Chart -->
        <form method="GET" action="{{ route('dashboard.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="date_from" class="form-label">From:</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">To:</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter Sales</button>
            </div>
        </form>

        <div class="row">
            <div class="col-md-6 mb-4">
                <h3>Customer Demographics</h3>
                {!! $customerChart->container() !!}
                {!! $customerChart->script() !!}
            </div>
            <div class="col-md-6 mb-4">
                <h3>Monthly Sales</h3>
                {!! $monthlySalesChart->container() !!}
                {!! $monthlySalesChart->script() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <h3>Yearly Sales</h3>
                {!! $yearlySalesChart->container() !!}
                {!! $yearlySalesChart->script() !!}
            </div>
            <div class="col-md-6 mb-4">
                <h3>Sales by Date Range</h3>
                {!! $salesBarChart->container() !!}
                {!! $salesBarChart->script() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <h3>Product Sales Distribution</h3>
                {!! $itemChart->container() !!}
                {!! $itemChart->script() !!}
            </div>
        </div>
    </div>
@endsection
