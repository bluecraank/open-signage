@extends('layouts.app')

@section('content')
    @can('read logs')
    <div class="title">{{ __('Logs') }}</div>

    <div class="card has-table">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Groups') }}
            </p>

            <div class="card-header-actions">
                @can('create groups')
                    <a href="{{ route('groups.create') }}" class="button is-primary is-small">
                        <span class="icon"><i class="mdi mdi-plus"></i></span>
                        <span>{{ __('Create group') }}</span>
                    </a>
                @endcan
            </div>
        </header>

        <div class="card-content">
            <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>{{ __('Time') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('IP') }}</th>
                        <th>{{ __('Message') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d.m.Y - H:i:s') }}</td>
                            <td>{{ $log->username }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td style="width:50%;">{{ $log->action }}</td>
                        </tr>
                    @endforeach
                    @if ($logs->count() == 0)
                        <tr>
                            <td colspan="5" class="has-text-centered">
                                {{ __('No logs found') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endcan

    @cannot('read logs')
        @include('unauthorized')
    @endcannot
@endsection
