@extends('layouts.app')

@section('content')
    @can('read groups')
        <h3 class="mb-3">{{ __('Groups') }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Overview') }}
                @can('create groups')
                    <a href="{{ route('groups.create') }}" class="btn-primary btn btn-sm float-end">
                        <span class="icon"><i class="bi-plus"></i></span>
                        <span>{{ __('Create group') }}</span>
                    </a>
                @endcan
                </h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>{{ __('Devices assigned') }}</th>
                            <th>{{ __('Assigned template') }}</th>
                            <th>{{ __('Created by') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($groups as $group)
                            <tr>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->devices->count() }}</td>
                                <td>{{ $group->presentation?->name ?? __('No template assigned') }}</td>
                                <td>{{ $group->created_by }}</td>
                                <td class="actions-cell">

                                    <form action="{{ route('groups.destroy', ['id' => $group->id]) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this group?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('read groups')
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('groups.update', ['id' => $group->id]) }}"><i
                                                        class="bi-pen"></i></a>
                                            @endcan
                                            @can('delete groups')
                                                <button class="btn btn-primary btn-sm" type="submit"><i
                                                        class="bi-trash"></i></button>
                                            @endcan
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($groups->count() == 0)
                            <tr>
                                <td colspan="5" class="text-center">
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
