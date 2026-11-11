@extends('layouts.app')

@section('title', __('Create group'))

@section('content')
    @include('partials.multiselect')

    <x-page-header :title="__('Create group')" :back="route('groups.index')" :backLabel="__('Groups')" />

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('groups.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input required type="text" class="form-control" name="name" placeholder="Name" id="name"
                            value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="presentation_id">{{ __('Template') }}<span class="text-danger">*</span></label>
                        <select required class="form-select" name="presentation_id" id="presentation_id">
                            <option value="">{{ __('Select a template') }}...</option>
                            @foreach ($presentations as $presentation)
                                <option value="{{ $presentation->id }}" @disabled(!$presentation->processed)>
                                    {{ $presentation->name }} @if (!$presentation->processed) ({{ __('In process') }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @can('assign device to group')
                        <div class="col-12">
                            <label class="form-label">{{ __('Devices') }}</label>
                            <select name="devices[]" class="multiselect" multiple="multiple">
                                @foreach ($devices as $device)
                                    <option value="{{ $device->id }}">{{ $device->description }} ({{ $device->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="synchronized" value="1"
                                id="synchronized">
                            <label class="form-check-label" for="synchronized">{{ __('Synchronize monitors') }}</label>
                            <div class="form-text">{{ __('All monitors in this group show the same slide at the same time.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('groups.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
