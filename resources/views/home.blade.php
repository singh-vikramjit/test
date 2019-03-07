@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Orignal Image</th>
                                <th>Scale Size</th>
                                <th>Scaleed Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($images as $image)
                                <tr>
                                    <td><a target="_blank" href="{{ $image->file_url }}"> {{ $image->filename }} </a></td>
                                    <td>{{ $image->scale_size }}</td>
                                    <td><a target="_blank" href="{{ $image->scaled_file_url }}"> {{ $image->scaled_filename }} </a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td align="center" colspan="3">No Record Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
