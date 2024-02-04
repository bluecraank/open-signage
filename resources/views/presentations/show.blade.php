@extends('layouts.app')

@section('content')
    <div class="title">{{ $presentation->name }}</div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Edit template') }}
            </p>

        </header>

        <div class="card-content">
            @can('read presentations')
                <div class="row">
                    <div class="column is-4">
                        <img width="500"
                            src="{{ $presentation->slides?->first()?->publicpreviewpath() ?? config('app.placeholder_image') }}"
                            alt="">

                        @can('delete presentations')
                            <form action="{{ route('presentations.destroy', $presentation->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('Are you sure to delete this template?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button is-danger is-smalls">{{ __('Delete template') }}</button>
                            </form>
                        @endcan
                    </div>

                    @cannot('update presentations')
                        <div class="col">
                            <p><b>Name:</b> {{ $presentation->name }}</p>
                            <p><b>{{ __('Description') }}:</b> {{ $presentation->description }}</p>
                            <p><b>{{ __('Slides') }}:</b> {{ $presentation->slides->count() }}</p>
                            <p><b>{{ __('Used by') }}:</b> {{ $presentation->devices->count() }}</p>
                        </div>
                    @endcannot
                    @can('update presentations')
                        <div class="col">
                            <form action="{{ route('presentations.update', $presentation->id) }}" method="post"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="field">
                                    <label class="form-label">{{ __('Description') }}<span class="text-danger">*</span></label>
                                    <input class="form-control" id="inputDescription" type="text" name="name" value="{{ $presentation->name }}" />
                                </div>

                                <div class="field">
                                    <label class="form-label">{{ __('Upload new file') }}</label>
                                    <div id="drop_zone" ondrop="window.dropHandler(event)"
                                        ondragover="window.dragOverHandler(event)">
                                        <div style="display: inline-block" class="file has-name is-normal" id="file-upload">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="file"
                                                    accept="application/pdf,video/mp4">
                                                <span class="file-cta">
                                                    <span class="file-icon">
                                                        <i class="mdi mdi-upload"></i>
                                                    </span>
                                                    <span class="file-label">
                                                        {{ __('Select file') }}...
                                                    </span>
                                                </span>
                                                <span class="file-name">
                                                    {{ __('No file selected') }}
                                                </span>
                                            </label>

                                        </div>
                                        <p class="ml-5" style="display: inline-block">{{ __('or drag and drop to upload') }}</p>
                                    </div>
                                    <span class="help is-danger">{{ __('Uploading new file will delete all slides!') }}</span>
                                </div>

                                <label class="form-label">&nbsp;</label>
                                @if ($presentation->processed)
                                    <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                                @else
                                    <button disabled type="button" class="button is-primary">{{ __('Save') }}
                                        ({{ __('In process') }})</button>
                                @endif
                            </form>
                        </div>
                    @endcan
                </div>

                <hr>

                <div class="subtitle pt-5">{{ __('Devices') }} ({{ $presentation->devices->count() }})</div>
                <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>{{ __('Location') }}</th>
                            <th>{{ __('Assigned') }}</th>
                            <th style="width:150px">{{ __('Link') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentation->devices as $device)
                            <tr>
                                <td>{{ $device->name }}</td>
                                <td>{{ $device->description }}</td>
                                <td>{{ $device->presentationFromGroup() ? __('By group') : __('Directly') }}</td>
                                <td class="text-center"><a
                                        href="{{ route('devices.show', $device->id) }}">{{ __('Go to device') }}</a>
                            </tr>
                        @endforeach

                        @if ($presentation->devices->count() == 0)
                            <tr>
                                <td class="text-center" colspan="4">{{ __('No devices assigned') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <hr>

                <div class="subtitle pt-5">{{ __('Groups') }} ({{ $presentation->groups->count() }})</div>
                <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>{{ __('Used by') }}</th>
                            <th style="width:150px">{{ __('Link') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentation->groups as $group)
                            <tr>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->devices->count() }}
                                    {{ trans_choice('Device|Devices', $group->devices->count()) }}</td>
                                <td class="text-center"><a
                                        href="{{ route('groups.show', $group->id) }}">{{ __('Go to group') }}</a>
                            </tr>
                        @endforeach

                        @if ($presentation->groups->count() == 0)
                            <tr>
                                <td class="text-center" colspan="3">{{ __('No groups assigned') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <hr>

                <div class="subtitle pt-5">{{ __('Schedules') }} ({{ $presentation->schedules()->count() }})</div>
                <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th style="width:150px">{{ __('Link') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentation->schedules() as $schedule)
                            <tr>
                                <td>{{ $schedule->name }}</td>
                                <td class="text-center"><a
                                        href="{{ route('schedules.show', $schedule->id) }}">{{ __('Go to schedule') }}</a>
                            </tr>
                        @endforeach

                        @if ($presentation->schedules()->count() == 0)
                            <tr>
                                <td class="text-center" colspan="2">{{ __('No schedules upcoming or active with this presentation') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <hr>

                <div class="slides pt-5">
                    @if (!$presentation->processed)
                        @livewire('show-slides', ['presentation' => $presentation])
                    @else
                        @include('livewire.show-slides')
                    @endif
                </div>
            @endcan
        </div>

    </div>
@endsection
