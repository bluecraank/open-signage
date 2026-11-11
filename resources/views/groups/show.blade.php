@extends('layouts.app')

@section('title', $group->name)

@section('content')
    @include('partials.multiselect')

    <x-page-header :title="$group->name" :subtitle="trans_choice('Device|Devices', $group->devices->count()) . ': ' . $group->devices->count()"
        :back="route('groups.index')" :backLabel="__('Groups')">
        <x-slot:actions>
            @can('delete groups')
                <x-delete-button size="md" :action="route('groups.destroy', $group->id)"
                    :confirm="__('Are you sure to delete this group?')" :label="__('Delete group')" />
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title-sm">{{ __('Preview') }}</div>
                    @php
                        // Presentation can have no slides (e.g. still processing)
                        $preview = $group->presentation?->slides->first()?->publicpreviewpath ?? config('app.placeholder_image');
                    @endphp
                    <img class="screen" src="{{ $preview }}" alt="">
                    @if ($group->presentation)
                        <a class="d-inline-block mt-3 small" href="{{ route('presentations.show', $group->presentation->id) }}">
                            {{ $group->presentation->name }} <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">{{ __('Edit group') }}</div>
                <div class="card-body">
                    @cannot('update groups')
                        <dl class="meta-list">
                            <div>
                                <dt>{{ __('Assigned template') }}</dt>
                                <dd>{{ $group->presentation?->name ?? __('No template assigned') }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('Synchronize monitors') }}</dt>
                                <dd>{{ $group->synchronized ? __('Yes') : __('No') }}</dd>
                            </div>
                        </dl>
                    @endcannot

                    @can('update groups')
                        <form action="{{ route('groups.update', $group->id) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Name<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="name" name="name" value="{{ $group->name }}" required />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="presentation_id">{{ __('Assigned template') }}<span class="text-danger">*</span></label>
                                    <select class="form-select" name="presentation_id" id="presentation_id" required>
                                        <option value="">{{ __('Select a template') }}...</option>
                                        @foreach ($presentations as $presentation)
                                            <option @selected($group->presentation_id == $presentation->id) value="{{ $presentation->id }}"
                                                @disabled(!$presentation->processed)>
                                                {{ $presentation->name }} @if (!$presentation->processed) ({{ __('In process') }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @can('assign device to group')
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Devices') }}</label>
                                        <select multiple="multiple" name="devices[]" class="multiselect">
                                            @foreach ($devices as $device)
                                                <option @selected($device->group_id == $group->id) value="{{ $device->id }}">
                                                    {{ $device->description }} ({{ $device->name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endcan

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="synchronized"
                                            value="1" id="synchronized" @checked($group->synchronized)>
                                        <label class="form-check-label" for="synchronized">{{ __('Synchronize monitors') }}</label>
                                        <div class="form-text">{{ __('All monitors in this group show the same slide at the same time.') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            </div>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
