@extends('layouts.base')

@section('body')
    <div class="container-md">
        @include('layouts.flash-messages')
        
        @php
            // Define available category options.
            $categories = [
                'Option 1' => 'Option 1',
                'Option 2' => 'Option 2',
                'Option 3' => 'Option 3',
                'Option 4' => 'Option 4',
                'Option 5' => 'Option 5'
            ];
        @endphp

        {!! Form::model($item, ['route' => ['items.update', $item->item_id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
        
        {{-- New: Name --}}
        {!! Form::label('name', 'Item Name', ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Existing: Description --}}
        {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
        {!! Form::text('description', null, ['class' => 'form-control', 'id' => 'description']) !!}
        @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- New: Category as Dropdown --}}
        {!! Form::label('category', 'Category', ['class' => 'form-label']) !!}
        {!! Form::select('category', $categories, null, ['class' => 'form-control', 'id' => 'category', 'placeholder' => 'Select Category']) !!}
        @error('category')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Cost Price --}}
        {!! Form::label('cost_price', 'Cost Price', ['class' => 'form-label']) !!}
        {!! Form::number('cost_price', null, ['min' => 0.00, 'step' => 0.01, 'class' => 'form-control', 'id' => 'cost_price']) !!}
        @error('cost_price')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Sell Price --}}
        {!! Form::label('sell_price', 'Sell Price', ['class' => 'form-label']) !!}
        {!! Form::number('sell_price', null, ['min' => 0.00, 'step' => 0.01, 'class' => 'form-control', 'id' => 'sell_price']) !!}
        @error('sell_price')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Quantity --}}
        {!! Form::label('quantity', 'Quantity', ['class' => 'form-label']) !!}
        {!! Form::number('quantity', null, ['class' => 'form-control', 'id' => 'quantity']) !!}
        @error('quantity')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Display current images if available --}}
        @if(isset($images) && $images->count() > 0)
            <div class="mb-3">
                <label class="form-label">Current Images</label>
                <div class="d-flex flex-wrap">
                    @foreach($images as $image)
                        <div class="m-1">
                            <img src="{{ Storage::url($image->image_path) }}" width="100" height="100" alt="Item Image">
                        </div>
                    @endforeach
                </div>
                <p class="text-muted">Uploading new images will replace all current images.</p>
            </div>
        @endif

        {!! Form::label('images', 'Upload New Images', ['class' => 'form-label']) !!}
        {!! Form::file('images[]', ['class' => 'form-control', 'multiple' => true]) !!}
        @error('images.*')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {!! Form::submit('Update Item', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endsection
