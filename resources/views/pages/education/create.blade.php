@push('upper-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endpush
@extends('layouts.admin')
@section('title', 'Create Education Post')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="h3 text-gray-800">{{ __('Create Education Post') }}</h1>
        <a href="{{ route('education') }}" class=""><-- Previous Page</a>
    </div>

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

    <form method="POST" action="{{ route('education.create.process') }}" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-lg-12 order-lg-1">

                <div class="card shadow mb-4">

                    <div class="card-body">

                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="title">{{ __('Title') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-Product" title="title"
                                            placeholder="{{ __('title') }}" value="{{ old('title') }}" name="title"
                                            required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="content">{{ __('Content') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="content" placeholder="{{ __('content') }}" name="content"
                                            required autofocus rows="20">{{ old('content') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="lat">{{ __('Lat') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-Product" title="lat"
                                            placeholder="{{ __('lat') }}" name="lat" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="lang">{{ __('Lang') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-Product" title="lang"
                                            placeholder="{{ __('lang') }}" name="lang" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="location">{{ __('Location') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-Product" title="location"
                                            placeholder="{{ __('location') }}" name="location" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label"
                                            for="form_registration">{{ __('Form Registration Link') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-Product"
                                            title="form_registration" placeholder="{{ __('form_registration') }}"
                                            name="form_registration" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="cover_image">{{ __('Cover Image') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-Product" title="cover_image"
                                            placeholder="{{ __('cover_image') }}" value="{{ old('cover_image') }}"
                                            name="cover_image" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label for="education_category"
                                            class="form-control-label">{{ __('Category') }}<span
                                            class="small text-danger">*</span></label>
                                        <select name="education_category" id="instance"
                                            class="form-control @error('education_category') is-invalid @enderror">
                                            @foreach ($data_category as $it)
                                                <option value="{{ $it->id }}">{{ $it->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="attachment">{{ __('Attachment') }}</label>
                                        <input type="file" class="form-control form-control-Product" title="attachment"
                                            placeholder="{{ __('attachment') }}" value="{{ old('attachment') }}"
                                            name="attachment" autofocus>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <a href="{{ route('education') }}" class="btn btn-warning">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        {{ __('Back') }}
                                    </a>
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
