@extends('layouts.app')

@section('content')
    @can('read logs')
        <h3>{{ __('Ongoing processing') }}</h3>
        <div class="card">
            <h5 class="card-header">
                {{ __('Overview') }}
            </h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Preview') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Slides') }}</th>
                            <th>{{ __('Slides updated') }}</th>
                            <th>{{ __('Created by') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($presentations as $presentation)
                            <tr>
                                <td>
                                    <a href="{{ route('presentations.show', ['id' => $presentation->id]) }}">
                                        <img src="{{ $presentation->slides->first()?->publicpreviewpath ?? config('app.placeholder_image') }}"
                                            class="img-thumbnail" style="max-height: 100px;">
                                    </a>
                                </td>
                                <td>{{ $presentation->name }}</td>
                                <td>{{ $presentation->slides->count() }}</td>
                                <td>{{ $presentation->slides->first()?->created_at?->format('d.m.Y H:i') ?? 'N/A' }}</td>
                                <td>{{ $presentation->author }}</td>
                                <td class="actions-cell">

                                    <form action="{{ route('presentations.ongoing.stop', $presentation->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure to stop this processing?') }}')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" type="submit"><i class="bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($presentations->count() == 0)
                            <tr>
                                <td colspan="7" class="text-center">
                                    {{ __('No templates found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
    @cannot('read presentations')
        @include('unauthorized')
    @endcannot
@endsection
