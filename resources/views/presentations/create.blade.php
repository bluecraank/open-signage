@extends('layouts.app')

@section('content')
    <h3 class="mb-3">{{ __('Templates') }}</h3>
    <div class="card">
        <h5 class="card-header">{{ __('Create template') }}</h5>
        <div class="card-body">
            <form action="{{ route('presentations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('Description') }}<span class="text-danger">*</span></label>
                    <input required type="text" class="form-control" id="inputDescription" name="name"
                        placeholder="{{ __('Description') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Upload file') }}<span class="text-danger">*</span></label>
                    <div id="drop_zone" ondrop="window.dropHandler(event)" ondragover="window.dragOverHandler(event)">
                        <div id="file-upload">
                            <input class="form-control file-input" type="file" name="file" required
                                accept="application/pdf,video/mp4" id="formFile">
                            <p class="mt-4" style="display: inline-block">{{ __('or drag and drop to upload') }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                <button type="reset" class="btn btn-danger">{{ __('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
