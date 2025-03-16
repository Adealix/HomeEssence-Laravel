@extends('layouts.base')
@section('body')

    <div class="row d-flex justify-content-between">
        <div class="col-12 col-lg-8 mt-5 order-details">
            <h1 class="my-5">Order # {{$customer->orderinfo_id}}</h1>
            <h4 class="mb-4">Shipping Info</h4>
            <p><b>Name:</b> {{$customer->lname}}  {{$customer->fname}}</p>
            <p><b>Phone:</b> {{$customer->phone}}</p>
            <p class="mb-4"><b>Address:</b> {{$customer->addressline}}</p>
            <p><b>Amount:</b> {{$total}} </p>
            <hr />
            <h4 class="my-4">Order Status:</h4>
            <p>{{$customer->status}}</p>
            <h4 class="my-4">Order Items:</h4>
            <hr />
          
            @foreach($orders as $order)
                <div class="cart-item my-1">
                    <div class="row my-5">
                        <div class="col-4 col-lg-2">
                            <img src="{{ Storage::url($order->image) }}" alt="" height="45" width="65" />
                        </div>
                        <div class="col-5 col-lg-5">
                            <a href="">{{ $order->name }}</a>
                            <p>Category: {{ $order->category }}</p>
                            <p>{{ $order->description }}</p>
                        </div>
                        <div class="col-4 col-lg-2 mt-4 mt-lg-0">
                            <p>${{ $order->sell_price }}</p>
                        </div>
                        <div class="col-4 col-lg-3 mt-4 mt-lg-0">
                            <p>{{ $order->quantity }} piece(s)</p>
                        </div>
                    </div>
                </div>
            @endforeach
            <hr />
            <div class="col-12 col-lg-3 mt-5">
                <h4 class="my-4">Status</h4>
                <form action="{{ route('admin.orderUpdate', $customer->orderinfo_id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="status">Example select</label>
                        <select class="form-control" id="status" name="status">
                            <option value="pending">Pending</option>
                            <option value="delivered">Delivered</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                    <input type="submit" value="Update Order Status" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
@endsection
