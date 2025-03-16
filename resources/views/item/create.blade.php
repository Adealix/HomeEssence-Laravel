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

        {!! Form::open(['route' => 'items.store', 'files' => true]) !!}
        
        {{-- New: Item Name --}}
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
        {!! Form::number('cost_price', 0.00, ['min' => 0.00, 'step' => 0.01, 'class' => 'form-control', 'id' => 'cost_price']) !!}
        @error('cost_price')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Sell Price --}}
        {!! Form::label('sell_price', 'Sell Price', ['class' => 'form-label']) !!}
        {!! Form::number('sell_price', 0.00, ['min' => 0.00, 'step' => 0.01, 'class' => 'form-control', 'id' => 'sell_price']) !!}
        @error('sell_price')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Quantity --}}
        {!! Form::label('qty', 'Quantity', ['class' => 'form-label']) !!}
        {!! Form::number('qty', 0, ['class' => 'form-control', 'id' => 'qty']) !!}
        @error('qty')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Images Upload --}}
        {!! Form::label('images', 'Upload Images', ['class' => 'form-label']) !!}
        {!! Form::file('images[]', ['class' => 'form-control', 'multiple' => true]) !!}
        @error('images.*')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {!! Form::submit('Add Item', ['class'=> "btn btn-primary mt-3"]) !!}
        {!! Form::close() !!}
    </div>
@endsection
