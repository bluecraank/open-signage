@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <x-page-header :title="$user->name" :subtitle="$user->email ?? __('This user has no mail')"
        :back="route('users.index')" :backLabel="__('Users')">
        <x-slot:actions>
            @can('delete users')
                <x-delete-button size="md" :action="route('users.destroy', $user->id)"
                    :confirm="__('Are you sure to delete this user?')" :label="__('Delete')" />
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="card">
        <div class="card-header">{{ __('Assign Role') }}</div>
        <div class="card-body p-4">
            @php $currentRoles = $user->getRoleNames()->toArray(); @endphp

            @can('update users')
                <form action="{{ route('users.update', $user->id) }}" method="post">
                    @method('PUT')
                    @csrf
            @endcan

            <div class="row g-3">
                @foreach ($roles as $role)
                    <div class="col-sm-6 col-lg-3">
                        <input class="btn-check" id="radio-{{ $role->id }}" type="radio" name="roles" value="{{ $role->id }}"
                            @checked(in_array($role->name, $currentRoles)) @cannot('update users') disabled @endcannot>
                        <label class="btn btn-outline-primary w-100 py-3" for="radio-{{ $role->id }}">
                            <i class="bi bi-shield-lock d-block fs-4 mb-1"></i>{{ $role->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            @can('update users')
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            @endcan
        </div>
    </div>
@endsection
