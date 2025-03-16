@extends('layouts.base')

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ isset($customer) ? __('Edit Customer Profile') : __('Create Customer Profile') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Left Column: Display the current profile picture in square format -->
                        <div class="col-md-4 text-center mb-4">
                            @if(isset($customer) && $customer->profile_picture)
                                <img src="{{ Storage::url('profile_pictures/' . $customer->profile_picture) }}" 
                                     alt="Profile Picture" class="img-fluid rounded-circle" 
                                     style="max-width:200px; height:200px; object-fit: contain;">
                            @else
                                <img src="{{ Storage::url('profile_pictures/defaultprofile.jpg') }}" 
                                     alt="Default Profile Picture" class="img-fluid rounded-circle" 
                                     style="max-width:200px; height:200px; object-fit: contain;">
                            @endif
                        </div>

                        <!-- Right Column: Customer Details Form -->
                        <div class="col-md-8">
                            @if(isset($customer))
                                <form method="POST" action="{{ route('customerprofile.update', $customer->id) }}" enctype="multipart/form-data">
                                @method('PUT')
                            @else
                                <form method="POST" action="{{ route('customerprofile.store') }}" enctype="multipart/form-data">
                            @endif
                                @csrf

                                <!-- Title Field as Dropdown -->
                                <div class="row mb-3">
                                    <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>
                                    <div class="col-md-6">
                                        <select id="title" name="title" class="form-control @error('title') is-invalid @enderror">
                                            <option value="">{{ __('Select Title') }}</option>
                                            <option value="Mr." {{ old('title', isset($customer) ? $customer->title : '') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                            <option value="Ms." {{ old('title', isset($customer) ? $customer->title : '') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                            <option value="Mrs." {{ old('title', isset($customer) ? $customer->title : '') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                        </select>
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- First Name Field -->
                                <div class="row mb-3">
                                    <label for="fname" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname', isset($customer) ? $customer->fname : '') }}" required>
                                        @error('fname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Last Name Field -->
                                <div class="row mb-3">
                                    <label for="lname" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname', isset($customer) ? $customer->lname : '') }}" required>
                                        @error('lname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address Line Field -->
                                <div class="row mb-3">
                                    <label for="addressline" class="col-md-4 col-form-label text-md-end">{{ __('Address Line') }}</label>
                                    <div class="col-md-6">
                                        <textarea id="addressline" class="form-control @error('addressline') is-invalid @enderror" name="addressline">{{ old('addressline', isset($customer) ? $customer->addressline : '') }}</textarea>
                                        @error('addressline')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Town Field -->
                                <div class="row mb-3">
                                    <label for="town" class="col-md-4 col-form-label text-md-end">{{ __('Town') }}</label>
                                    <div class="col-md-6">
                                        <input id="town" type="text" class="form-control @error('town') is-invalid @enderror" name="town" value="{{ old('town', isset($customer) ? $customer->town : '') }}">
                                        @error('town')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Zipcode Field -->
                                <div class="row mb-3">
                                    <label for="zipcode" class="col-md-4 col-form-label text-md-end">{{ __('Zipcode') }}</label>
                                    <div class="col-md-6">
                                        <input id="zipcode" type="text" class="form-control @error('zipcode') is-invalid @enderror" name="zipcode" value="{{ old('zipcode', isset($customer) ? $customer->zipcode : '') }}">
                                        @error('zipcode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone Field -->
                                <div class="row mb-3">
                                    <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', isset($customer) ? $customer->phone : '') }}">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Profile Picture Field -->
                                <div class="row mb-3">
                                    <label for="profile_picture" class="col-md-4 col-form-label text-md-end">{{ __('Profile Picture') }}</label>
                                    <div class="col-md-6">
                                        <input id="profile_picture" type="file" class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture">
                                        @error('profile_picture')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Save Profile') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> {{-- End row --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
