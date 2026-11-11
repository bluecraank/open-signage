@extends('layouts.app')

@section('title', __('Ongoing processing'))

@section('content')
    <x-page-header :title="__('Ongoing processing')" :subtitle="__('Uploads that are still being converted into slides')" />

    <div class="card">
        @if ($presentations->isEmpty())
            <x-empty-state icon="check2-circle" :text="__('Nothing is being processed right now')" />
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="ps-4">{{ __('Description') }}</th>
                            <th>{{ __('Slides') }}</th>
                            <th>{{ __('Created by') }}</th>
                            <th>{{ __('Started') }}</th>
                            <th class="pe-4 text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentations as $presentation)
                            <tr>
                                <td class="ps-4">
                                    <a class="table-link" href="{{ route('presentations.show', ['id' => $presentation->id]) }}">{{ $presentation->name }}</a>
                                </td>
                                <td><span class="loader me-2 align-middle"></span>{{ $presentation->slides->count() }}</td>
                                <td class="text-muted">{{ $presentation->author }}</td>
                                <td class="text-muted">{{ $presentation->updated_at?->diffForHumans() }}</td>
                                <td class="actions-cell pe-4">
                                    @can('update presentations')
                                        <form action="{{ route('presentations.ongoing.stop', $presentation->id) }}" method="POST"
                                            onsubmit="return confirm(@js(__('Are you sure to stop this processing?')))">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-stop-circle me-1"></i>{{ __('Stop') }}</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
