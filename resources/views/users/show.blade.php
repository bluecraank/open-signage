@extends('layouts.app')

@section('content')
    <div class="title">{{ $user->name }}</div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Edit user') }}
            </p>

        </header>

        <div class="card-content">
            <div class="columns">
                @can('read users')
                    @can('update users')
                        <div class="column">
                            <form action="{{ route('users.update', $user->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="field">
                                    <label for="" class="label">{{ __('Assign Role') }}</label>
                                    <div class="columns">
                                        @foreach ($roles as $role)
                                            <div class="column is-1">
                                                <label class="checkbox">
                                                    <input @if(in_array($role->name, $user->getRoleNames()->toArray())) checked @endif type="checkbox" name="roles[]" value="{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <label for="" class="label">&nbsp;</label>
                                <button type="submit" class="button is-primary">{{ __('Save User') }}</button>
                            </form>
                            @can('delete users')
                                <form class="pt-2" action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this user?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger is-smalls">{{ __('Delete User') }}</button>
                                </form>
                            @endcan
                        </div>
                    @endcan
                @endcan
            </div>
        </div>
    </div>
@endsection
