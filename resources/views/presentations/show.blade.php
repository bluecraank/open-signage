    @extends('layouts.app')

    @section('content')
        <h3 class="mb-3">
            {{ __('Template') }}: {{ $presentation->name }}</h3>
        <div class="card">
            <h5 class="card-header">
                {{ __('Edit template') }}
            </h5>

            <div class="card-body">
                @can('read presentations')
                    <div class="row">
                        <div class="col-4">
                            <img class="w-100"
                                src="{{ $presentation->slides?->first()?->publicpreviewpath() ?? config('app.placeholder_image') }}"
                                alt="">

                            @can('delete presentations')
                                <form class="mt-5" action="{{ route('presentations.destroy', $presentation->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this template?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete template') }}</button>
                                </form>
                            @endcan
                        </div>

                        <div class="col-8">
                            @cannot('update presentations')
                                <p><b>Name:</b> {{ $presentation->name }}</p>
                                <p><b>{{ __('Description') }}:</b> {{ $presentation->description }}</p>
                                <p><b>{{ __('Slides') }}:</b> {{ $presentation->slides->count() }}</p>
                                <p><b>{{ __('Used by') }}:</b> {{ $presentation->devices->count() }}</p>
                            @endcannot
                            @can('update presentations')
                                <form action="{{ route('presentations.update', $presentation->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="field">
                                        <label class="form-label">{{ __('Description') }}<span class="text-danger">*</span></label>
                                        <input class="form-control" id="inputDescription" type="text" name="name"
                                            value="{{ $presentation->name }}" />
                                    </div>


                                    <div class="mb-3 mt-4">
                                        <label class="form-label">{{ __('Upload file') }}<span class="text-danger">*</span></label>
                                        <div id="drop_zone" ondrop="window.dropHandler(event)"
                                            ondragover="window.dragOverHandler(event)">
                                            <div id="file-upload">
                                                <input class="form-control file-input" type="file" name="file" required
                                                    accept="application/pdf,video/mp4" id="formFile">
                                                <p class="mt-4" style="display: inline-block">
                                                    {{ __('or drag and drop to upload') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    @if ($presentation->processed)
                                        <button @if (!$presentation->processed) disabled @endif type="submit"
                                            class="btn btn-primary">{{ __('Save') }} @if (!$presentation->processed)
                                                ({{ __('In process') }})
                                            @endif
                                        </button>
                                    @else
                                        <button disabled type="submit" class="btn btn-primary">{{ __('Save') }} @if (!$presentation->processed)
                                                ({{ __('In process') }})
                                            @endif
                                        </button>
                                    @endif
                                </form>
                            @endcan
                        </div>

                        <hr style="margin-top: 20px">

                        <div class="p-3">
                            <h5 class="pt-1">{{ __('Devices') }} ({{ $presentation->devices->count() }})</h5>

                            <table class="table table-striped">
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
                        </div>

                        <hr>

                        <div class="p-3">
                            <h5 class="pt-5">{{ __('Groups') }} ({{ $presentation->groups->count() }})</h5>
                            <table class="table table-striped">
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
                        </div>

                        <hr>

                        <div class="p-3">
                            <h5 class="pt-5">{{ __('Schedules') }} ({{ $presentation->getSchedules()->count() }})</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th style="width:150px">{{ __('Link') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($presentation->getSchedules() as $schedule)
                                        <tr>
                                            <td>{{ $schedule->name }}</td>
                                            <td class="text-center"><a
                                                    href="{{ route('schedules.show', $schedule->id) }}">{{ __('Go to schedule') }}</a>
                                        </tr>
                                    @endforeach

                                    @if ($presentation->getSchedules()->count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="2">
                                                {{ __('No schedules upcoming or active with this presentation') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

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
