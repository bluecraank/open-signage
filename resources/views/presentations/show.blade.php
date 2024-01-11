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
                <div class="columns">
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
                        <div class="column">
                            <p><b>Name:</b> {{ $presentation->name }}</p>
                            <p><b>{{ __('Description') }}:</b> {{ $presentation->description }}</p>
                            <p><b>{{ __('Slides') }}:</b> {{ $presentation->slides->count() }}</p>
                            <p><b>{{ __('Used by') }}:</b> {{ $presentation->devices->count() }}</p>
                        </div>
                    @endcannot
                    @can('update presentations')
                        <div class="column">
                            <form action="{{ route('presentations.update', $presentation->id) }}" method="post"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="field">
                                    <label class="label">{{ __('Description') }}<span class="has-text-danger">*</span></label>
                                    <input class="input" type="text" name="name" value="{{ $presentation->name }}" />
                                </div>

                                <div class="field">
                                    <label class="label">{{ __('Upload new file') }}</label>
                                    <div class="file has-name" id="file-upload">
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
                                                {{ __('No file selected') }} (max: 100 Mb)
                                            </span>
                                        </label>

                                    </div>
                                    <span class="help is-danger">{{ __('Uploading new file will delete all slides!') }}</span>

                                    <script>
                                        const fileInput = document.querySelector('#file-upload input[type=file]');
                                        fileInput.onchange = () => {
                                            if (fileInput.files.length > 0) {
                                                const fileName = document.querySelector('#file-upload .file-name');
                                                fileName.textContent = fileInput.files[0].name;
                                            }
                                        }
                                    </script>
                                </div>

                                <label class="label">&nbsp;</label>
                                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
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
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentation->devices as $device)
                            <tr>
                                <td>{{ $device->name }}</td>
                                <td>{{ $device->description }}</td>
                                <td>{{ $device->presentationFromGroup() ? __('By group') : __('Directly') }}</td>
                            </tr>
                        @endforeach

                        @if ($presentation->devices->count() == 0)
                            <tr>
                                <td class="has-text-centered" colspan="3">{{ __('No devices assigned') }}</td>
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
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentation->groups as $group)
                            <tr>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->devices->count() }}
                                    {{ trans_choice('Device|Devices', $group->devices->count()) }}</td>
                            </tr>
                        @endforeach

                        @if ($presentation->groups->count() == 0)
                            <tr>
                                <td class="has-text-centered" colspan="2">{{ __('No groups assigned') }}</td>
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
