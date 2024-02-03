<nav class="navbar navbar-expand-lg bg-secondary-subtle mb-5 p-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">open-signage</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @can('read devices')
                    <li class="nav-item">
                        <a href="{{ route('devices.index') }}"
                            class="nav-link @if (str_contains(\Request::route()->getName(), 'devices.')) active @endif">
                            {{ __('Devices') }}
                        </a>
                    </li>
                @endcan

                @can('read groups')
                    <li class="nav-item">
                        <a href="{{ route('groups.index') }}"
                            class="nav-link @if (str_contains(\Request::route()->getName(), 'groups.')) active @endif">
                            {{ __('Groups') }}
                        </a>
                    </li>
                @endcan

                @can('read presentations')
                    <li class="nav-item">
                        <a href="{{ route('presentations.index') }}"
                            class="nav-link @if (str_contains(\Request::route()->getName(), 'presentations.')) active @endif">
                            {{ __('Templates') }}
                        </a>
                    </li>
                @endcan

                @can('read schedules')
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}"
                            class="nav-link @if (str_contains(\Request::route()->getName(), 'schedules.')) active @endif">
                            {{ __('Schedules') }}
                        </a>
                    </li>
                @endcan

                @can('manage settings')
                    <li class="nav-item dropdown">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('Settings') }}
                        </button>
                        <ul class="dropdown-menu">
                            @can('read users')
                                <li><a href="{{ route('users.index') }}" class="dropdown-item">
                                        {{ __('Users') }}
                                    </a></li>
                            @endcan
                            <li><a href="{{ route('settings.index') }}" class="dropdown-item">
                                    {{ __('Monitorsettings') }}
                                </a></li>
                            @can('read logs')
                                <li><a href="{{ route('logs.index') }}" class="dropdown-item">
                                        {{ __('Logs') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            </ul>
        </div>
        <div class="d-flex">
            <li class="nav-item dropdown" style="list-style: none">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img height="25px"
                        src="https://ui-avatars.com/api/?rounded=true&background=a0a0a0&size=30&name={{ Auth::user()->name }}">
                    <span>{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu">
                    <li class="themeButton" style="cursor: pointer"><span class="dropdown-item">
                            <i class="bi-sun-fill light-mode d-none p-2"></i>
                            <i class="bi-moon-fill dark-mode d-none p-2"></i>
                            {{ __('Change theme') }}
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="text-center mb-1" style="font-size: 12px;">{{ Auth::user()->roles[0]->name }}</li>
                    @if (!config('app.sso_enabled') || !isset($_SERVER[config('app.sso_http_header_user_key')]))
                        <li class="text-center"><a onclick="document.getElementById('logout-form').submit()"
                                class="dropdown-item" href="#">{{ __('Logout') }}</a></li>
                        <form class="pl-3" id="logout-form" action="/logout" method="POST">
                            @csrf
                        </form>
                    @endif
                    <script>
                        const darkMode = document.querySelector('.dark-mode');
                        const lightMode = document.querySelector('.light-mode');
                        const body = document.querySelector('body');
                        const switchMode = document.querySelector('.themeButton');

                        switchMode.addEventListener('click', () => {
                            if (body.dataset.bsTheme === 'dark') {
                                body.dataset.bsTheme = '';
                                localStorage.setItem('theme', 'light');
                                darkMode.classList.remove('d-none');
                                lightMode.classList.add('d-none');
                            } else {
                                body.dataset.bsTheme = 'dark';
                                localStorage.setItem('theme', 'dark');
                                lightMode.classList.remove('d-none');
                                darkMode.classList.add('d-none');
                            }

                        });

                        if (localStorage.getItem('theme') === 'dark') {
                            body.dataset.bsTheme = 'dark';
                            lightMode.classList.remove('d-none');
                        } else {
                            body.dataset.bsTheme = '';
                            darkMode.classList.remove('d-none');
                        }
                    </script>
                </ul>
            </li>

        </div>
    </div>
</nav>
