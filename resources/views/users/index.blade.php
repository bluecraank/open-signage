@extends('layouts.app')

@section('content')
    @can('read users')
    <h3 class="mb-3">{{ __('Users') }}</h3>

        <div class="card">
            <h5 class="card-header">
                {{ __('Overview') }}
            </h5>
            <div class="card-body">
                <table class="table table-striped">
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
                                        <div class="btn-group" role="group">
                                        @can('update users')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('users.update', ['id' => $user->id]) }}"><i
                                                    class="bi-pen"></i></a>
                                        @endcan
                                        @can('delete users')
                                            <button class="btn btn-primary btn-sm" type="submit"><i
                                                    class="bi-trash"></i></button>
                                        @endcan
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($users->count() == 0)
                            <tr>
                                <td colspan="5" class="text-center">
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
