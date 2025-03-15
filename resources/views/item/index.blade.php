@extends('layouts.base')
@section('body')
    <div id="items" class="container">
        @include('layouts.flash-messages')
        <a class="btn btn-primary" href="{{ route('items.create') }}" role="button">Add Item</a>
        {{-- Import Form --}}
        <form method="POST" enctype="multipart/form-data" action="{{ route('item.import') }}">
            @csrf
            <input type="file" id="uploadName" name="item_upload" required>
            <button type="submit" class="btn btn-info btn-primary">Import Excel File</button>
        </form>
        <div class="card-body" style="height: 210px;">
            <input type="text" id="itemSearch" placeholder="--search--">
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-striped table-hover', 'id' => 'itable']) !!}
        </div>
        <p class="mt-3 text-muted">
            Use the carousel arrows in the Images column to navigate through multiple images.
        </p>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
