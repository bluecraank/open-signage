@extends('layouts.app')

@section('title', __('Templates'))

@section('content')
    @can('read presentations')
        <x-page-header :title="__('Templates')" :subtitle="$countUsed . ' ' . __('in use') . ' · ' . $countUnused . ' ' . __('unused')">
            <x-slot:actions>
                @can('create presentations')
                    <a href="{{ route('presentations.create') }}" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>{{ __('Create template') }}
                    </a>
                @endcan
            </x-slot:actions>
        </x-page-header>

        <div class="card">
            @if ($presentations->isEmpty())
                <x-empty-state icon="images" :text="__('No templates found')" />
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="ps-4">{{ __('Preview') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Slides') }}</th>
                                <th>{{ __('Used by') }}</th>
                                <th>{{ __('Slides updated') }}</th>
                                <th class="pe-4 text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($presentations as $presentation)
                                @php
                                    $list = [];
                                    if ($presentation->devices->count() > 0) {
                                        $list[] = $presentation->devices->count() . ' ' . trans_choice('Device|Devices', $presentation->devices->count());
                                    }
                                    if ($presentation->groups->count() > 0) {
                                        $list[] = $presentation->groups->count() . ' ' . trans_choice('Group|Groups', $presentation->groups->count());
                                    }
                                    $scheduleCount = $presentation->getSchedules()->count();
                                    if ($scheduleCount > 0) {
                                        $list[] = $scheduleCount . ' ' . trans_choice('Schedule|Schedules', $scheduleCount);
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('presentations.show', ['id' => $presentation->id]) }}">
                                            <img src="{{ $presentation->slides->first()?->publicpreviewpath ?? config('app.placeholder_image') }}"
                                                class="thumb" alt="" loading="lazy">
                                        </a>
                                    </td>
                                    <td>
                                        <a class="table-link" href="{{ route('presentations.show', ['id' => $presentation->id]) }}">{{ $presentation->name }}</a>
                                        <div class="small text-muted">{{ $presentation->author }}</div>
                                    </td>
                                    <td>
                                        @if ($presentation->processed)
                                            {{ $presentation->slides->count() }}
                                        @else
                                            <span class="status status-pending">{{ __('In process') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (count($list) > 0)
                                            <span class="status status-online">{{ implode(', ', $list) }}</span>
                                        @else
                                            <span class="status status-neutral">{{ __('unused') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $presentation->slides->first()?->created_at?->format('d.m.Y H:i') ?? 'N/A' }}</td>
                                    <td class="actions-cell pe-4">
                                        <a class="btn btn-outline-secondary btn-sm icon-btn" title="{{ __('Edit template') }}"
                                            href="{{ route('presentations.show', ['id' => $presentation->id]) }}"><i class="bi bi-pencil"></i></a>
                                        @can('delete presentations')
                                            <x-delete-button :action="route('presentations.destroy', ['id' => $presentation->id])"
                                                :confirm="__('Are you sure to delete this template?')" />
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endcan
    @cannot('read presentations')
        @include('unauthorized')
    @endcannot
@endsection
