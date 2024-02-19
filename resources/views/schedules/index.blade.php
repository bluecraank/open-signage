@extends('layouts.app')

@section('content')
    @can('read schedules')
        <h3 class="mb-3">{{ __('Schedules') }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Running schedules') }}

                <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm float-end">
                    <i class="bi-plus"></i>
                    {{ __('Create schedule') }}
                </a>
            </h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Created by') }}</th>
                            <th>Name</th>
                            <th>{{ __('Since') }}</th>
                            <th>{{ __('Until') }}</th>
                            <th>{{ __('Applies to') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($activeSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->created_by }}</td>
                                <td>{{ $schedule->name }}</td>
                                <td>{{ $schedule->startDate() }}</td>
                                <td>{{ $schedule->endDate() }}</td>
                                <td>{{ $schedule->appliesTo() }}</td>
                                <td class="actions-cell">
                                    <form action="{{ route('schedules.destroy', ['id' => $schedule->id]) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this schedule?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <div class="btn-group" role="group">
                                        @can('read schedules')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('schedules.update', ['id' => $schedule->id]) }}"><i
                                                    class="bi-pen"></i></a>
                                        @endcan
                                        @can('delete schedules')
                                            <button class="btn btn-primary btn-sm" type="submit"><i
                                                    class="bi-trash"></i></button>
                                        @endcan
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($activeSchedules->count() == 0)
                            <tr>
                                <td colspan="6" class="text-center">
                                    {{ __('No schedules found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-5">
            <h5 class="card-header">
                {{ __('Upcoming schedules') }}
            </h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Created by') }}</th>
                            <th>Name</th>
                            <th>{{ __('Starts') }}</th>
                            <th>{{ __('Ends') }}</th>
                            <th>{{ __('Applies to') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($upcomingSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->created_by }}</td>
                                <td>{{ $schedule->name }}</td>
                                <td>{{ $schedule->startDate() }}</td>
                                <td>{{ $schedule->endDate() }}</td>
                                <td>{{ $schedule->appliesTo() }}</td>
                                <td class="actions-cell">
                                    <form action="{{ route('schedules.destroy', ['id' => $schedule->id]) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this schedule?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <div class="btn-group" role="group">
                                        @can('read schedules')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('schedules.update', ['id' => $schedule->id]) }}"><i
                                                    class="bi-pen"></i></a>
                                        @endcan
                                        @can('delete schedules')
                                            <button class="btn btn-primary btn-sm" type="submit"><i
                                                    class="bi-trash"></i></button>
                                        @endcan
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($upcomingSchedules->count() == 0)
                            <tr>
                                <td colspan="6" class="text-center">
                                    {{ __('No schedules found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-5">
            <h5 class="card-header">
                {{ __('Past schedules') }}
            </h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Created by') }}</th>
                            <th>Name</th>
                            <th>{{ __('Starts') }}</th>
                            <th style="width:200px;">{{ __('Ends') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pastSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->created_by }}</td>
                                <td>{{ $schedule->name }}</td>
                                <td>{{ $schedule->startDate() }}</td>
                                <td>{{ $schedule->endDate() }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        @if ($pastSchedules->count() == 0)
                            <tr>
                                <td colspan="4" class="text-center">
                                    {{ __('No schedules found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
    @cannot('read schedules')
        @include('unauthorized')
    @endcannot
@endsection
