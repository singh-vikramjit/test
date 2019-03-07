@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Upload Image') }}</div>

                <div class="card-body">

                    @if ($errors->has('error'))
                        <span class="alert alert-danger invalid-feedback" role="alert" style="display: block;">
                            <strong>{{ $errors->first('error') }}</strong>
                        </span>
                    @endif

                    <form method="POST" enctype="multipart/form-data" action="{{ route('uploadFile') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>

                            <div class="col-md-6">
                                <input id="image" type="file" accept="image/*" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image" required>

                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="scale" class="col-md-4 col-form-label text-md-right">{{ __('Scale Size') }}</label>

                            <div class="col-md-6">
                                <input id="scale" type="number" min="10" max="2000" value="{{ old('scale') }}" class="form-control{{ $errors->has('scale') ? ' is-invalid' : '' }}" required name="scale" placeholder="100" >

                                @if ($errors->has('scale'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('scale') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Upload') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if (!empty($orignal_file_url))
    <div class="row">
        <div class="col-md-8">
            <img width="100%" style="padding: 20px 30px 10px;" src="{{ $orignal_file_url }}">
        </div>
        <div class="col-md-4">
            <img width="100%" style="padding: 20px 30px 10px;" src="{{ $scaled_file_url }}">
        </div>
    </div>                   
    @endif
</div>
@endsection
