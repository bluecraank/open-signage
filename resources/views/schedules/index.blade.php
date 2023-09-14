@extends('layouts.app')

@section('content')
    @can('read schedules')
        <div class="title">{{ __('Schedules') }}</div>

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Active schedules') }}
                </p>

                <div class="card-header-actions">
                    @can('create schedules')
                        <a href="{{ route('schedules.create') }}" class="button is-primary is-small">
                            <span class="icon"><i class="mdi mdi-plus"></i></span>
                            <span>{{ __('Create schedule') }}</span>
                        </a>
                    @endcan
                </div>
            </header>

            <div class="card-content">
                <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                    <thead>
                        <tr>
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
                                <td>{{ $schedule->name }}</td>
                                <td>{{ $schedule->startDate() }}</td>
                                <td>{{ $schedule->endDate() }}</td>
                                <td>{{ $schedule->appliesTo() }}</td>
                                <td class="actions-cell">
                                    <form action="{{ route('schedules.destroy', ['id' => $schedule->id]) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this schedule?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        @can('read schedules')
                                            <a class="button is-info is-small"
                                                href="{{ route('schedules.update', ['id' => $schedule->id]) }}"><i
                                                    class="mdi mdi-pen"></i></a>
                                        @endcan
                                        @can('delete schedules')
                                            <button class="button is-danger is-small" type="submit"><i
                                                    class="mdi mdi-trash-can"></i></button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($activeSchedules->count() == 0)
                            <tr>
                                <td colspan="5" class="has-text-centered">
                                    {{ __('No schedules found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="columns">
            <div class="column is-7">
                <div class="card has-table mt-5">
                    <header class="card-header">
                        <p class="card-header-title">
                            {{ __('Upcoming schedules') }}
                        </p>
                    </header>

                    <div class="card-content">
                        <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                            <thead>
                                <tr>
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
                                        <td>{{ $schedule->name }}</td>
                                        <td>{{ $schedule->startDate() }}</td>
                                        <td>{{ $schedule->endDate() }}</td>
                                        <td>{{ $schedule->appliesTo() }}</td>
                                        <td class="actions-cell">
                                            <form action="{{ route('schedules.destroy', ['id' => $schedule->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('Are you sure to delete this schedule?') }}')">
                                                @method('DELETE')
                                                @csrf
                                                @can('read schedules')
                                                    <a class="button is-info is-small"
                                                        href="{{ route('schedules.update', ['id' => $schedule->id]) }}"><i
                                                            class="mdi mdi-pen"></i></a>
                                                @endcan
                                                @can('delete schedules')
                                                    <button class="button is-danger is-small" type="submit"><i
                                                            class="mdi mdi-trash-can"></i></button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($upcomingSchedules->count() == 0)
                                    <tr>
                                        <td colspan="5" class="has-text-centered">
                                            {{ __('No schedules found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="column is-5">
                <div class="card has-table mt-5 ">
                    <header class="card-header">
                        <p class="card-header-title">
                            {{ __('Past schedules') }}
                        </p>
                    </header>

                    <div class="card-content">
                        <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>{{ __('Starts') }}</th>
                                    <th>{{ __('Ends') }}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pastSchedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->name }}</td>
                                        <td>{{ $schedule->startDate() }}</td>
                                        <td>{{ $schedule->endDate() }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                @if ($pastSchedules->count() == 0)
                                    <tr>
                                        <td colspan="4" class="has-text-centered">
                                            {{ __('No schedules found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @cannot('read schedules')
        @include('unauthorized')
    @endcannot
@endsection
