@extends('layouts.app')

@section('content')
    @can('read logs')
    <h3 class="mb-3">{{ __('Logs') }}</h3>

    <div class="card">
        <h5 class="card-header">
          {{ __('Overview') }}
        </h5>
        <div class="card-body">
            <table class="table table-striped">
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
                            <td colspan="4" class="text-center">
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
