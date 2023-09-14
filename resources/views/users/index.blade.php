@extends('layouts.app')

@section('content')
    @can('read users')
        <div class="title">{{ __('Users') }}</div>

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    {{ __('Users') }}
                </p>
            </header>

            <div class="card-content">
                <table class="table is-narrow is-striped is-hoverable is-fullwidth is-fullwidth">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>{{ __('Mail') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email ?? __('This user has no mail') }}</td>
                                <td>{{ $user->getRoleNames()[0] ?? __('No roles') }}</td>
                                <td class="actions-cell">

                                    <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure to delete this user?') }}')">
                                        @method('DELETE')
                                        @csrf
                                        @can('update users')
                                            <a class="button is-info is-small"
                                                href="{{ route('users.update', ['id' => $user->id]) }}"><i
                                                    class="mdi mdi-pen"></i></a>
                                        @endcan
                                        @can('delete users')
                                            <button class="button is-danger is-small" type="submit"><i
                                                    class="mdi mdi-trash-can"></i></button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($users->count() == 0)
                            <tr>
                                <td colspan="5" class="has-text-centered">
                                    {{ __('No users found, should not be possible :D') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
    @cannot('read users')
        @include('unauthorized')
    @endcannot
@endsection
