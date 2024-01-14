@extends('layouts.app')

@section('content')
    @can('read presentations')
        <div class="title">{{ __('Templates') }}</div>

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Templates') }}
                </p>

                <div class="card-header-actions">
                    @can('create presentations')
                        <a href="{{ route('presentations.create') }}" class="button is-primary is-small">
                            <span class="icon"><i class="mdi mdi-plus"></i></span>
                            <span>{{ __('Create template') }}</span>
                        </a>
                    @endcan
                </div>
            </header>

            <div class="card-content">
                <table class="table is-narrow is-striped is-hoverable is-fullwidth">
                    <thead>
                        <tr>
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
                                <td>{{ $presentation->name }}</td>
                                <td>{{ $presentation->slides->count() }}</td>
                                <td>{{ $presentation->slides->first()->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $presentation->devices->count() }}
                                    {{ trans_choice('Device|Devices', $presentation->devices->count()) }},

                                    {{ $presentation->groups->count() }}
                                    {{ trans_choice('Group|Groups', $presentation->groups->count()) }}
                                </td>
                                <td>{{ $presentation->author }}</td>
                                <td class="actions-cell">

                                    <form action="{{ route('presentations.destroy', ['id' => $presentation->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this template?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        @can('read presentations')
                                            <a class="button is-info is-small"
                                                href="{{ route('presentations.update', ['id' => $presentation->id]) }}"><i
                                                    class="mdi mdi-pen"></i></a>
                                        @endcan
                                        @can('delete presentations')
                                            <button class="button is-danger is-small" type="submit"><i
                                                    class="mdi mdi-trash-can"></i></button>
                                        @endcan
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
