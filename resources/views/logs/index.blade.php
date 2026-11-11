@extends('layouts.app')

@section('title', __('Logs'))

@section('content')
    @can('read logs')
        <x-page-header :title="__('Logs')" :subtitle="__('Changes made in the interface and by the system')" />

        <div class="card">
            @if ($logs->isEmpty())
                <x-empty-state icon="journal-text" :text="__('No logs found')" />
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="ps-4">{{ __('Time') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th class="pe-4">{{ __('IP') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td class="ps-4 text-nowrap text-muted">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                                    <td class="text-nowrap">
                                        @if ($log->username === 'System')
                                            <span class="status status-neutral">System</span>
                                        @else
                                            {{ $log->username }}
                                        @endif
                                    </td>
                                    <td>{{ $log->action }}</td>
                                    <td class="pe-4 text-muted small">{{ $log->ip_address }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-3">{{ $logs->links() }}</div>
    @endcan

    @cannot('read logs')
        @include('unauthorized')
    @endcannot
@endsection
