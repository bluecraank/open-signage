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
            <div class="columns">
                @can('read presentations')
                    <div class="column is-4">
                        <img width="500" src="{{ $presentation->slides?->first()?->publicpreviewpath() ?? 'https://picsum.photos/333/214' }}" alt="">
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
                                    <label for="" class="label">{{ __('Template name') }}</label>
                                    <input class="input" type="text" name="name" value="{{ $presentation->name }}" />
                                </div>
                                <div class="field">
                                    <label for="" class="label">{{ __('Description') }}</label>
                                    <input class="input" type="text" name="description"
                                        value="{{ $presentation->description }}" />
                                </div>

                                <div class="field">
                                    <label for="" class="label">{{ __('Upload new pdf') }}</label>
                                    <div class="file has-name" id="file-upload">
                                        <label class="file-label">
                                            <input class="file-input" type="file" name="file" accept=".pdf">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="mdi mdi-upload"></i>
                                                </span>
                                                <span class="file-label">
                                                    {{ __('Select pdf') }}...
                                                </span>
                                            </span>
                                            <span class="file-name">
                                                {{ __('No file selected') }} (max: 100 Mb)
                                            </span>
                                        </label>
                                    </div>

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

                                <label for="" class="label">&nbsp;</label>
                                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                            </form>
                        </div>
                    @endcan
                @endcan
            </div>

            <hr>

            <div class="subtitle pt-5">{{ __('Devices') }}</div>
            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Last seen') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($presentation->devices as $device)
                        <tr>
                            <td>{{ $device->name }}</td>
                            <td>{{ $device->description }}</td>
                            <td>{{ $device->last_seen ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ route('devices.destroy', ['id' => $device->id]) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this device?') }}')">
                                    @method('DELETE')
                                    @csrf
                                    @can('update devices')
                                        <a class="button is-info is-small"
                                            href="{{ route('devices.update', ['id' => $device->id]) }}"><i
                                                class="mdi mdi-pen"></i></a>
                                    @endcan
                                    @can('delete devices')
                                        <button class="button is-danger is-small" type="submit"><i
                                                class="mdi mdi-trash-can"></i></button>
                                    @endcan
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($presentation->devices->count() == 0)
                        <tr>
                            <td class="has-text-centered" colspan="4">{{ __('No devices assigned') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <hr>

            <div class="slides pt-5">
                <div class="subtitle">{{ __('Slides') }}</div>
                @if (!$presentation->processed)
                    <div class="box" style="display:flex">
                        <button style="justify-content:center" class="button is-loading has-background-white pr-4"></button>
                        <span style="justify-content: center"
                            class="pl-4">{{ __('Slides are being processed, please wait...') }}</span>
                    </div>
                @endif
                @foreach ($presentation->slides as $slide)
                    <div class="slide box">
                        <div class="columns gapless">
                            <img src="{{ $slide->publicpreviewpath() }}">
                            <div class="column">
                                <div>Name: {{ $slide->name }}</div>
                                <div>{{ __('Filename') }}: {{ $slide->name_on_disk }} </div>
                                <div>{{ __('Order') }}: {{ $slide->order }}</div>
                                <div>{{ __('Created') }}: {{ $slide->created_at }}</div>
                                <div>&nbsp;</div>
                                <div><a target="_blank" href="{{ $slide->publicpath() }}">{{ __('Preview') }}</a></div>
                            </div>

                            <div class="column">

                            </div>

                            @can('delete slides')
                                <form action="{{ route('slides.destroy', $slide->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this slide?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger is-smalls"><i
                                            class="mdi mdi-trash-can"></i></button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
