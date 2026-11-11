@extends('layouts.app')

@section('title', '403')

@section('content')
    <div class="@guest auth-page @endguest">
        <div class="card mx-auto" style="max-width: 32rem">
            <x-empty-state icon="shield-lock" :text="__('You are not allowed to perform this action')">
                <div class="small mt-2">{{ __('If you think this is a mistake, please contact your administrator') }}</div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mt-3">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('Back to App') }}</a>
            </x-empty-state>
        </div>
    </div>
@endsection
