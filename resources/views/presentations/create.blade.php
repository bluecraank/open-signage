@extends('layouts.app')

@section('title', __('Create template'))

@section('content')
    <x-page-header :title="__('Create template')" :back="route('presentations.index')" :backLabel="__('Templates')" />

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('presentations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label" for="inputDescription">{{ __('Description of presentation') }}<span class="text-danger">*</span></label>
                    <input required type="text" class="form-control" id="inputDescription" name="name"
                        value="{{ old('name') }}" placeholder="{{ __('Description of presentation') }}">
                </div>

                <label class="form-label">{{ __('Upload file') }}<span class="text-danger">*</span></label>
                @include('partials.file-upload', ['required' => true])

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i>{{ __('Create') }}</button>
                    <a href="{{ route('presentations.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
