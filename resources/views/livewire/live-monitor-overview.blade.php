<div class="card" wire:poll.10s>
    <h5 class="card-header">
        {{ __('Overview') }}
        @can('create devices')
            <a href="{{ route('devices.create') }}" class="btn-primary btn btn-sm float-end">
                <span class="icon"><i class="bi-plus"></i></span>
                <span>{{ __('Create device') }}</span>
            </a>
        @endcan
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <select class="form-select form-select-sm w-50 mb-1" name="" id="select">
                    <option value="">Name</option>
                    <option value="">Gruppe</option>
                    <option value="">Status</option>
                    <option value="">Aktualisiert</option>
                </select>
            </div>
            <div class="col">

            </div>
            <div class="col"></div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:201px" scope="col">{{ __('Current slide') }}</th>
                    <th scope="col">Name</th>
                    <th scope="col">{{ __('Location') }}</th>
                    <th scope="col">{{ __('Presentation') }}</th>
                    <th scope="col">{{ __('Last connection') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($devices as $device)
                    <tr>
                        <td style="width:201px">
                            @php
                                $device_pres = $device->getPresentation();
                                $slides = $device_pres?->slides;
                                if ($slides?->toArray() != null) {
                                    $currentSlide = array_key_exists($device->current_slide ?? 0, $slides?->toArray()) ? $slides?->toArray()[$device->current_slide ?? 0] : $slides?->toArray()[0];
                                    $preview = $currentSlide['publicpreviewpath'] ?? config('app.placeholder_image');
                                } else {
                                    $preview = config('app.placeholder_image');
                                }
                            @endphp
                            <img class="img-thumbnail" style="max-height: 100px;" src="{{ $preview }}" alt="">
                        </td>
                        <td>{{ $device->name }}</td>
                        <td>{{ $device->description }}</td>
                        <td>{{ $device_pres?->name ?? __('No template assigned') }}
                            @if ($device->presentationFromSchedule())
                                <br> <small class="has-text-success"><i
                                        class="mdi mdi-checkbox-marked-circle-outline"></i>
                                    {{ __('Inherited by schedule') }}</small>
                            @elseif($device->presentationFromGroup())
                                <br> <small class="has-text-success"><i
                                        class="mdi mdi-checkbox-marked-circle-outline"></i>
                                    {{ __('Inherited by group') }}</small>
                            @endif
                        </td>
                        <td>
                            @php $active = $device->isActive(); @endphp
                            @if ($active && $device->registered)
                                <div class="badge bg-success">{{ Carbon::parse($device->last_seen)->diffForHumans() }}
                                </div>
                            @elseif(!$active && $device->registered)
                                <div class="badge bg-danger">{{ Carbon::parse($device->last_seen)->diffForHumans() }}
                                </div>
                            @elseif(!$device->registered)
                                {{ __('Waiting for registration...') }}
                            @endif
                        </td>
                        <td class="actions-cell">

                            <form action="{{ route('devices.destroy', ['id' => $device->id]) }}" method="POST"
                                onsubmit="return confirm('{{ __('Are you sure to delete this device?') }}')">
                                @method('DELETE')
                                @csrf
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    @can('read devices')
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('devices.update', ['id' => $device->id]) }}"><i
                                                class="bi-pen"></i></a>
                                    @endcan
                                    @can('delete devices')
                                        <button class="btn btn-primary btn-sm" type="submit"><i
                                                class="bi-trash"></i></button>
                                    @endcan
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($devices->count() == 0)
                    <tr>
                        <td colspan="8" class="has-text-centered">
                            {{ __('No devices found') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
