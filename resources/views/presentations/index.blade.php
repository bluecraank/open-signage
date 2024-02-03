@extends('layouts.app')

@section('content')
    @can('read presentations')
        <h3 class="mb-3">{{ __('Templates') }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Overview') }}
                @can('create presentations')
                    <a href="{{ route('presentations.create') }}" class="btn-primary btn btn-sm float-end">
                        <span class="icon"><i class="bi-plus"></i></span>
                        <span>{{ __('Create template') }}</span>
                    </a>
                @endcan
            </h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Preview') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Slides') }}</th>
                            <th>{{ __('Slides updated') }}</th>
                            <th>{{ __('Used by') }}</th>
                            <th>{{ __('Created by') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentations as $presentation)
                            <tr>
                                <td>
                                    <a href="{{ route('presentations.show', ['id' => $presentation->id]) }}">
                                        <img src="{{ $presentation->slides->first()?->publicpreviewpath ?? config('app.placeholder_image') }}"
                                            class="img-thumbnail" style="max-height: 100px;">
                                    </a>
                                </td>
                                <td>{{ $presentation->name }}</td>
                                <td>{{ $presentation->slides->count() }}</td>
                                <td>{{ $presentation->slides->first()?->created_at?->format('d.m.Y H:i') ?? 'N/A' }}</td>
                                <td>

                                    @if ($presentation->devices->count() > 0)
                                        {{ $presentation->devices->count() }}
                                        {{ trans_choice('Device|Devices', $presentation->devices->count()) }},
                                    @endif

                                    @if ($presentation->groups->count() > 0)
                                        {{ $presentation->groups->count() }}
                                        {{ trans_choice('Group|Groups', $presentation->groups->count()) }},
                                    @endif

                                    @if ($presentation->schedules()->count() > 0)
                                        {{ $presentation->schedules()->count() }}
                                        {{ trans_choice('Schedule|Schedules', $presentation->schedules()->count()) }},
                                    @endif
                                </td>
                                <td>{{ $presentation->author }}</td>
                                <td class="actions-cell">

                                    <form action="{{ route('presentations.destroy', ['id' => $presentation->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this template?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('read presentations')
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('presentations.update', ['id' => $presentation->id]) }}"><i
                                                        class="bi-pen"></i></a>
                                            @endcan
                                            @can('delete presentations')
                                                <button class="btn btn-sm btn-primary" type="submit"><i
                                                        class="bi-trash"></i></button>
                                            @endcan
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($presentations->count() == 0)
                            <tr>
                                <td colspan="5" class="has-text-centered">
                                    {{ __('No templates found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
    @cannot('read presentations')
        @include('unauthorized')
    @endcannot
@endsection
