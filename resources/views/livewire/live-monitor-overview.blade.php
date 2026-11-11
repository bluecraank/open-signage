<div wire:poll.10s>
    <div class="row g-3 mb-4">
        @foreach ([
            ['total', __('Devices'), 'display', 'primary'],
            ['online', __('Online'), 'wifi', 'success'],
            ['offline', __('Offline'), 'wifi-off', 'danger'],
            ['pending', __('Waiting for registration'), 'hourglass-split', 'warning'],
        ] as [$key, $label, $icon, $color])
            <div class="col-6 col-lg-3">
                <div class="card stat-card">
                    <span class="stat-icon bg-{{ $color }}-subtle text-{{ $color }}-emphasis"><i class="bi bi-{{ $icon }}"></i></span>
                    <div>
                        <div class="stat-value">{{ $stats[$key] }}</div>
                        <div class="stat-label">{{ $label }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <span>{{ __('Overview') }}</span>
            <div class="d-flex align-items-center gap-2">
                <label for="DeviceSortBy" class="small text-muted text-nowrap mb-0">{{ __('Sort by') }}</label>
                <select wire:model.live="sort_by" class="form-select form-select-sm" name="sort_by" id="DeviceSortBy">
                    <option value="name">Name</option>
                    <option value="group">{{ __('Group') }}</option>
                    <option value="presentation">{{ __('Template') }}</option>
                    <option value="status">Status</option>
                    <option value="updated">{{ __('Updated') }}</option>
                </select>
            </div>
        </div>

        @if ($devices->isEmpty())
            <x-empty-state icon="display" :text="__('No devices found')">
                <div class="small mt-2">{{ __('Open :url on a monitor to add it.', ['url' => url('/discover')]) }}</div>
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="ps-4">{{ __('Current slide') }}</th>
                            <th>Name</th>
                            <th>{{ __('Template') }}</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($devices as $device)
                            @php $device_pres = $device->getPresentation(); @endphp
                            <tr wire:key="device-{{ $device->id }}">
                                <td class="ps-4">
                                    <a href="{{ route('devices.show', $device->id) }}">
                                        <img class="thumb" src="{{ $device->currentSlidePreview() }}" alt="" loading="lazy">
                                    </a>
                                </td>
                                <td>
                                    <a class="table-link" href="{{ route('devices.show', $device->id) }}">{{ $device->name }}</a>
                                    <div class="small text-muted"><i class="bi bi-geo-alt"></i> {{ $device->description }}</div>
                                    @if ($device->group)
                                        <div class="small text-muted"><i class="bi bi-collection"></i> {{ $device->group->name }}
                                            @if ($device->group->synchronized)
                                                <i class="bi bi-arrow-repeat" title="{{ __('Synchronize monitors') }}"></i>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{ $device_pres?->name ?? __('No template assigned') }}
                                    @if ($device->presentationFromSchedule())
                                        <div class="source-hint"><i class="bi bi-calendar-check"></i> {{ __('Inherited by schedule') }}</div>
                                    @elseif($device->presentationFromGroup())
                                        <div class="source-hint"><i class="bi bi-collection"></i> {{ __('Inherited by group') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <x-device-status :device="$device" :active="$online[$device->id]" />
                                </td>
                                <td class="actions-cell pe-4">
                                    <a class="btn btn-outline-secondary btn-sm icon-btn" title="{{ __('Edit device') }}"
                                        href="{{ route('devices.show', ['id' => $device->id]) }}"><i class="bi bi-pencil"></i></a>
                                    @can('delete devices')
                                        <x-delete-button :action="route('devices.destroy', ['id' => $device->id])"
                                            :confirm="__('Are you sure to delete this device?')" />
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
