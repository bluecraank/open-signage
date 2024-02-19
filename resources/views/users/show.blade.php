@extends('layouts.app')

@section('content')
    <h3 class="mb-3">{{ __('User') }}: {{ $user->name }}</h3>
    <div class="card">
        <header class="card-header">
            {{ __('Edit user') }}
        </header>

        <div class="card-body">
            <div class="row">
                @can('read users')
                    @can('update users')
                        <div class="col">
                            @can('delete users')
                                <form id="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('Are you sure to delete this user?') }}')">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endcan

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" disabled readonly id="name" class="form-control"
                                    value="{{ $user->name }}">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('E-Mail') }}</label>
                                <input type="text" disabled readonly id="email" class="form-control"
                                    placeholder="{{ __('This user has no mail') }}" value="{{ $user->email }}">
                            </div>

                            <div class="mb-3">
                                <form action="{{ route('users.update', $user->id) }}" method="post">
                                    @method('PUT')
                                    @csrf
                                    <label class="form-label">{{ __('Assign Role') }}</label>
                                    <div class="row">
                                        @foreach ($roles as $role)
                                            <div class="col-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="radio-{{ $role->id }}"
                                                        @if (in_array($role->name, $user->getRoleNames()->toArray())) checked @endif type="radio"
                                                        name="roles" value="{{ $role->id }}">
                                                    <label class="form-check-label" for="radio-{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            @can('delete users')
                                <button type="button" class="btn btn-danger"
                                    onclick="return confirm('{{ __('Are you sure to delete this user?') }}') && document.getElementById('deleteForm').submit()">{{ __('Delete') }}</button>
                            @endcan
                            </form>
                        </div>
                    </div>
                @endcan
            @endcan
        </div>
    </div>
    </div>
@endsection
