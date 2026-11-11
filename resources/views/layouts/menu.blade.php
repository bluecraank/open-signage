<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-xl">
        <a class="navbar-brand" href="/">
            <span class="brand-mark"><i class="bi bi-display"></i></span>
            {{ config('app.name') }}
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav gap-lg-1 ms-lg-3">
                @can('read devices')
                    <li class="nav-item">
                        <a href="{{ route('devices.index') }}"
                            class="nav-link @if (request()->routeIs('devices.*')) active @endif">
                            <i class="bi bi-display"></i> {{ __('Devices') }}
                        </a>
                    </li>
                @endcan

                @can('read groups')
                    <li class="nav-item">
                        <a href="{{ route('groups.index') }}"
                            class="nav-link @if (request()->routeIs('groups.*')) active @endif">
                            <i class="bi bi-collection"></i> {{ __('Groups') }}
                        </a>
                    </li>
                @endcan

                @can('read presentations')
                    <li class="nav-item">
                        <a href="{{ route('presentations.index') }}"
                            class="nav-link @if (request()->routeIs('presentations.*') && !request()->routeIs('presentations.ongoing')) active @endif">
                            <i class="bi bi-images"></i> {{ __('Templates') }}
                        </a>
                    </li>
                @endcan

                @can('read schedules')
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}"
                            class="nav-link @if (request()->routeIs('schedules.*')) active @endif">
                            <i class="bi bi-calendar-week"></i> {{ __('Schedules') }}
                        </a>
                    </li>
                @endcan

                @canany(['manage settings', 'read users', 'read logs'])
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if (request()->routeIs('settings.*', 'users.*', 'logs.*', 'presentations.ongoing')) active @endif"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sliders"></i> {{ __('Administration') }}
                        </a>
                        <ul class="dropdown-menu">
                            @can('manage settings')
                                <li><a href="{{ route('settings.index') }}" class="dropdown-item">
                                        <i class="bi bi-gear me-2"></i>{{ __('Monitorsettings') }}</a></li>
                            @endcan
                            @can('read users')
                                <li><a href="{{ route('users.index') }}" class="dropdown-item">
                                        <i class="bi bi-people me-2"></i>{{ __('Users') }}</a></li>
                            @endcan
                            @can('read logs')
                                <li><a href="{{ route('logs.index') }}" class="dropdown-item">
                                        <i class="bi bi-journal-text me-2"></i>{{ __('Logs') }}</a></li>
                                <li><a href="{{ route('presentations.ongoing') }}" class="dropdown-item">
                                        <i class="bi bi-hourglass-split me-2"></i>{{ __('Ongoing processing') }}</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item d-flex align-items-center">
                    @livewire('poll-presentation-process')
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-link nav-link icon-btn themeButton"
                        title="{{ __('Change theme') }}" aria-label="{{ __('Change theme') }}">
                        <i class="bi bi-sun light-mode d-none"></i>
                        <i class="bi bi-moon-stars dark-mode d-none"></i>
                    </button>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="avatar">{{ Auth::user()->initials() }}</span>
                        <span class="d-lg-none d-xl-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                            <div class="small text-muted">{{ Auth::user()->getRoleNames()->first() ?? __('No roles') }}</div>
                        </li>
                        @if (!config('app.sso_enabled') || !isset($_SERVER[config('app.sso_http_header_user_key')]))
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
