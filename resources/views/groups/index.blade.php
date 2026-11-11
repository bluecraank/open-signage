@extends('layouts.app')

@section('title', __('Groups'))

@section('content')
    @can('read groups')
        <x-page-header :title="__('Groups')" :subtitle="__('Show the same template on several monitors')">
            <x-slot:actions>
                @can('create groups')
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('Create group') }}
                    </a>
                @endcan
            </x-slot:actions>
        </x-page-header>

        <div class="card">
            @if ($groups->isEmpty())
                <x-empty-state icon="collection" :text="__('No groups found')" />
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>{{ __('Devices assigned') }}</th>
                                <th>{{ __('Assigned template') }}</th>
                                <th>{{ __('Created by') }}</th>
                                <th class="pe-4 text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <td class="ps-4">
                                        <a class="table-link" href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
                                        @if ($group->synchronized)
                                            <div class="source-hint"><i class="bi bi-arrow-repeat"></i> {{ __('Synchronize monitors') }}</div>
                                        @endif
                                    </td>
                                    <td><span class="badge rounded-pill count-badge">{{ $group->devices->count() }}</span></td>
                                    <td>{{ $group->presentation?->name ?? __('No template assigned') }}</td>
                                    <td class="text-muted">{{ $group->created_by }}</td>
                                    <td class="actions-cell pe-4">
                                        <a class="btn btn-outline-secondary btn-sm icon-btn" title="{{ __('Edit group') }}"
                                            href="{{ route('groups.show', ['id' => $group->id]) }}"><i class="bi bi-pencil"></i></a>
                                        @can('delete groups')
                                            <x-delete-button :action="route('groups.destroy', ['id' => $group->id])"
                                                :confirm="__('Are you sure to delete this group?')" />
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
    @cannot('read groups')
        @include('unauthorized')
    @endcannot
@endsection
