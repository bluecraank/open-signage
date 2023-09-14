@extends('layouts.app')

@section('content')
    <div class="title">{{ __('Create device') }}</div>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Create device') }}
            </p>
        </header>

        <div class="card-content">
            <form action="{{ route('devices.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label class="label">Name</label>
                    <input required type="text" class="input" name="name" placeholder="Name">
                </div>

                <div class="field">
                    <label class="label">{{ __('IP Address') }}</label>
                    <input required type="text" class="input" name="ip_address" placeholder="{{ __('IP Address') }}">
                </div>

                <div class="field">
                    <label class="label">{{ __('Description') }}</label>
                    <input required type="text" class="input" name="description" placeholder="{{ __('Description') }}">
                </div>

                <div class="columns">
                    <div class="column is-6">

                        <div class="field">
                            <label class="label">{{ __('Template') }}</label>
                            <div class="select is-fullwidth">
                                <select required name="presentation_id" id="">
                                    <option value="0">{{ __('Select a template') }}...</option>
                                    @foreach ($presentations as $presentation)
                                        <option value="{{ $presentation->id }}">{{ $presentation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">{{ __('Group') }}</label>
                            <div class="select is-fullwidth">
                                <select required name="group_id" id="">
                                    <option value="0">{{ __('Select a group') }}...</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="button is-primary">{{ __('Save') }}</button>
                <button type="reset" class="button is-danger is-pulled-right">{{ __('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
