@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    @can('read users')
        <x-page-header :title="__('Users')" :subtitle="__('Users are created on their first login through Active Directory')" />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>{{ __('Mail') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th class="pe-4 text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar">{{ $user->initials() }}</span>
                                        <a class="table-link" href="{{ route('users.show', ['id' => $user->id]) }}">{{ $user->name }}</a>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $user->email ?? __('This user has no mail') }}</td>
                                <td>
                                    @php $role = $user->getRoleNames()->first(); @endphp
                                    <span class="status {{ $role ? ($role === 'Super Administrator' ? 'status-online' : 'status-neutral') : 'status-pending' }}">
                                        {{ $role ?? __('No roles') }}</span>
                                </td>
                                <td class="actions-cell pe-4">
                                    @can('update users')
                                        <a class="btn btn-outline-secondary btn-sm icon-btn" title="{{ __('Edit user') }}"
                                            href="{{ route('users.show', ['id' => $user->id]) }}"><i class="bi bi-pencil"></i></a>
                                    @endcan
                                    @can('delete users')
                                        <x-delete-button :action="route('users.destroy', ['id' => $user->id])"
                                            :confirm="__('Are you sure to delete this user?')" />
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
    @cannot('read users')
        @include('unauthorized')
    @endcannot
@endsection
