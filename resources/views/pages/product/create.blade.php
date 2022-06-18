@push('upper-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endpush
@extends('layouts.admin')
@section('title', 'Create Product')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Create Product') }}</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('product.create.process') }}" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-lg-12 order-lg-1">

                <div class="card shadow mb-4">

                    <div class="card-body">

                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="type" class="form-control-label">{{ __('Type') }}<span
                                            class="small text-danger">*</span></label>
                                        <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror">
                                            <option value="jual">Jual</option>
                                            <option value="cicil">Cicil</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="title">{{ __('Title') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-Product" title="title"
                                            placeholder="{{ __('title') }}" value="{{ old('title') }}" name="title" required
                                            autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="address">{{ __('Address') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="address" placeholder="{{ __('address') }}" name="address" required
                                            rows="3">{{ old('address') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="description">{{ __('Description') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="description" placeholder="{{ __('description') }}" name="description" required
                                            rows="7">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="type" class="form-control-label">{{ __('Category') }}<span
                                            class="small text-danger">*</span></label>
                                        <select name="category" id="category"
                                            class="form-control @error('category') is-invalid @enderror">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="description">{{ __('Suitable For') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="suitable" placeholder="{{ __('suitable') }}" name="suitable" required
                                            rows="7">{{ old('suitable') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="price">{{ __('Price') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-Product" title="price"
                                            placeholder="{{ __('price') }}" value="{{ old('price') }}" name="price"
                                            required autofocus>

                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label"
                                            for="discounted_price">{{ __('Discounted Price') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-Product"
                                            title="discounted_price" placeholder="{{ __('discounted_price') }}"
                                            value="{{ old('discounted_price') }}" name="discounted_price" required
                                            autofocus>

                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="photo">{{ __('Photo') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-Product" title="photo"
                                            placeholder="{{ __('photo') }}" value="{{ old('photo') }}" name="photo"
                                            required autofocus>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

@endsection
