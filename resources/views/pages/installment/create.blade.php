@push('upper-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endpush
@extends('layouts.admin')
@section('title', 'Create Installment')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="h3 text-gray-800">{{ __('Create Installment') }}</h1>
        <a href="{{ route('installment') }}" class="">
            <-- Previous Page</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
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

    <form method="POST" action="{{ route('installment.create.process') }}" autocomplete="off"
        enctype="multipart/form-data">
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
                                        <label class="form-control-label" for="area">{{ __('Area (m2)') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="number" min="0" step="1"
                                            class="form-control form-control-Product" title="area"
                                            placeholder="{{ __('area') }}" value="{{ old('area') }}" name="area"
                                            required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="address">{{ __('Address') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="address" placeholder="{{ __('address') }}" name="address"
                                            required rows="3">{{ old('address') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="description">{{ __('Description') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="description" placeholder="{{ __('description') }}"
                                            name="description" required rows="7">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="type" class="form-control-label">{{ __('Category') }}<span
                                                class="small text-danger">*</span></label>
                                        <select name="category" id="category"
                                            class="form-control @error('category') is-invalid @enderror">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="description">{{ __('Suitable For') }}<span
                                                class="small text-danger">*</span></label>
                                        <textarea class="form-control form-control-Product" title="suitable" placeholder="{{ __('suitable') }}"
                                            name="suitable" required rows="7">{{ old('suitable') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="price">{{ __('Price') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-Product" title="price"
                                            placeholder="{{ __('price') }}" value="{{ old('price') }}" name="price"
                                            required autofocus>
                                    </div>


                                    <div class="form-group">
                                        <label class="form-control-label" for="dp">{{ __('DP') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-Product" title="dp"
                                            placeholder="{{ __('dp') }}" min=0 max=50 value="{{ old('dp') }}"
                                            name="dp" required autofocus>
                                            <small>in %, max 50%</small>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="tenor">{{ __('Tenor') }}<span
                                                class="small text-danger">*</span></label>
                                        <select name="tenor" id="tenor"
                                            class="form-control @error('tenor') is-invalid @enderror">
                                            <option value="">{{ __('Select Tenor') }}</option>
                                            <option value="12" {{ old('tenor') == '12' ? 'selected' : '' }}>12
                                                {{ __('Month') }} - 1 {{ __('Year') }}</option>
                                            <option value="24" {{ old('tenor') == '24' ? 'selected' : '' }}>24
                                                {{ __('Month') }} - 2 {{ __('Year') }}</option>
                                            <option value="36" {{ old('tenor') == '36' ? 'selected' : '' }}>36
                                                {{ __('Month') }} - 3 {{ __('Year') }}</option>
                                            <option value="48" {{ old('tenor') == '48' ? 'selected' : '' }}>48
                                                {{ __('Month') }} - 4 {{ __('Year') }}</option>
                                            <option value="60" {{ old('tenor') == '60' ? 'selected' : '' }}>60
                                                {{ __('Month') }} - 5 {{ __('Year') }}</option>
                                            <option value="72" {{ old('tenor') == '72' ? 'selected' : '' }}>72
                                                {{ __('Month') }} - 6 {{ __('Year') }}</option>
                                            <option value="84" {{ old('tenor') == '84' ? 'selected' : '' }}>84
                                                {{ __('Month') }} - 7 {{ __('Year') }}</option>
                                            <option value="96" {{ old('tenor') == '96' ? 'selected' : '' }}>96
                                                {{ __('Month') }} - 8 {{ __('Year') }}</option>
                                            <option value="108" {{ old('tenor') == '108' ? 'selected' : '' }}>108
                                                {{ __('Month') }} - 9 {{ __('Year') }}</option>
                                            <option value="120" {{ old('tenor') == '120' ? 'selected' : '' }}>120
                                                {{ __('Month') }} - 10 {{ __('Year') }}</option>
                                            <option value="132" {{ old('tenor') == '132' ? 'selected' : '' }}>132
                                                {{ __('Month') }} - 11 {{ __('Year') }}</option>
                                            <option value="144" {{ old('tenor') == '144' ? 'selected' : '' }}>144
                                                {{ __('Month') }} - 12 {{ __('Year') }}</option>
                                            <option value="156" {{ old('tenor') == '156' ? 'selected' : '' }}>156
                                                {{ __('Month') }} - 13 {{ __('Year') }}</option>
                                            <option value="168" {{ old('tenor') == '168' ? 'selected' : '' }}>168
                                                {{ __('Month') }} - 14 {{ __('Year') }}</option>
                                            <option value="180" {{ old('tenor') == '180' ? 'selected' : '' }}>180
                                                {{ __('Month') }} - 15 {{ __('Year') }}</option>
                                            <option value="192" {{ old('tenor') == '192' ? 'selected' : '' }}>192
                                                {{ __('Month') }} - 16 {{ __('Year') }}</option>
                                            <option value="204" {{ old('tenor') == '204' ? 'selected' : '' }}>204
                                                {{ __('Month') }} - 17 {{ __('Year') }}</option>
                                            <option value="216" {{ old('tenor') == '216' ? 'selected' : '' }}>216
                                                {{ __('Month') }} - 18 {{ __('Year') }}</option>
                                            <option value="228" {{ old('tenor') == '228' ? 'selected' : '' }}>228
                                                {{ __('Month') }} - 19 {{ __('Year') }}</option>
                                            <option value="240" {{ old('tenor') == '240' ? 'selected' : '' }}>240
                                                {{ __('Month') }} - 20 {{ __('Year') }}</option>
                                            <option value="252" {{ old('tenor') == '252' ? 'selected' : '' }}>252
                                                {{ __('Month') }} - 21 {{ __('Year') }}</option>
                                            <option value="264" {{ old('tenor') == '264' ? 'selected' : '' }}>264
                                                {{ __('Month') }} - 22 {{ __('Year') }}</option>
                                            <option value="276" {{ old('tenor') == '276' ? 'selected' : '' }}>276
                                                {{ __('Month') }} - 23 {{ __('Year') }}</option>
                                            <option value="288" {{ old('tenor') == '288' ? 'selected' : '' }}>288
                                                {{ __('Month') }} - 24 {{ __('Year') }}</option>
                                            <option value="300" {{ old('tenor') == '300' ? 'selected' : '' }}>300
                                                {{ __('Month') }} - 25 {{ __('Year') }}</option>
                                            <option value="312" {{ old('tenor') == '312' ? 'selected' : '' }}>312
                                                {{ __('Month') }} - 26 {{ __('Year') }}</option>
                                            <option value="324" {{ old('tenor') == '324' ? 'selected' : '' }}>324
                                                {{ __('Month') }} - 27 {{ __('Year') }}</option>
                                            <option value="336" {{ old('tenor') == '336' ? 'selected' : '' }}>336
                                                {{ __('Month') }} - 28 {{ __('Year') }}</option>
                                            <option value="348" {{ old('tenor') == '348' ? 'selected' : '' }}>348
                                                {{ __('Month') }} - 29 {{ __('Year') }}</option>
                                            <option value="360" {{ old('tenor') == '360' ? 'selected' : '' }}>360
                                                {{ __('Month') }} - 30 {{ __('Year') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="photo">{{ __('Photo Product') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-Product" title="photo"
                                            placeholder="{{ __('photo') }}" value="{{ old('photo') }}"
                                            name="photo" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="photo_drone">{{ __('Photo Drone View') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-Product" title="photo_drone"
                                            placeholder="{{ __('photo_drone') }}" value="{{ old('photo_drone') }}"
                                            name="photo_drone" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="photo_denah">{{ __('Photo Map View') }}<span
                                                class="small text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-Product" title="photo_denah"
                                            placeholder="{{ __('photo_denah') }}" value="{{ old('photo_denah') }}"
                                            name="photo_denah" required autofocus>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <a href="{{ route('installment') }}" class="btn btn-warning">
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
