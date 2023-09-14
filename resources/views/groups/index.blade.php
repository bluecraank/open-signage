@extends('layouts.app')

@section('content')
    @can('read groups')
    <div class="title">{{ __('Groups') }}</div>

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
                        <th>Name</th>
                        <th>{{ __('Devices') }}</th>
                        <th>{{ __('Assigned template') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>{{ $group->name }}</td>
                            <td>{{ $group->devices->count() }}</td>
                            <td>{{ $group->presentation?->name ?? __('No template assigned') }}</td>
                            <td class="actions-cell">

                                <form action="{{ route('groups.destroy', ['id' => $group->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this group?') }}')">
                                    @method('DELETE')
                                    @csrf
                                    @can('read presentations')
                                        <a class="button is-info is-small"
                                            href="{{ route('groups.update', ['id' => $group->id]) }}"><i
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
                    @if ($groups->count() == 0)
                        <tr>
                            <td colspan="5" class="has-text-centered">
                                {{ __('No groups found') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endcan
    @cannot('read groups')
        @include('unauthorized')
    @endcannot
@endsection
