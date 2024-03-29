<nav class="navbar bg-white shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <div class="flex items-center flex-1">
            <!-- Left Side Of Navbar -->
            <div class="subnav collapsable">
                @include('layouts.subnav')
            </div>

            <!-- Right Side Of Navbar -->
            <div class="ml-auto">
                <!-- Authentication Links -->
                @guest
                    <div class="pl-0 mb-0 hidden md:block">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </div>
                    <dropdown align="right" width="200px" class="block md:hidden">
                        <div slot="trigger">
                            <button class="burger" style="outline: none;">
                                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                            </button>
                        </div>

                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </dropdown>
                @else
                    <dropdown align="right" width="200px">
                        <div slot="trigger">
                            <div class="block md:hidden">
                                <button class="burger" style="outline: none;">
                                    <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                                </button>
                            </div>
                            <div class="hidden md:block">
                                <button class="dropdown-toggle-link flex items-center text-secondary-800 no-underline text-base focus:outline-none" v-pre>
                                    <div class="w-10 h-10 mr-3 overflow-hidden rounded-full border border-secondary-700">
                                        <img src="/{{ auth()->user()->avatar_path ?: 'storage/avatars/default.jpg' }}" class="w-full h-full object-cover" />
                                    </div>
                                    <span>{{ auth()->user()->name }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="subnav">
                            @include('layouts.subnav')
                        </div>
                        <a href="{{ route('home') }}" class="dropdown-menu-link w-full text-left">View Dashboard</a>
                        <a href="{{ route('users.edit', auth()->user()->slug) }}" class="dropdown-menu-link w-full text-left">Edit Profile</a>
                        <a href="#" class="dropdown-menu-link w-full text-left"
                            onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">  
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </dropdown>
                @endguest
            </div>
        </div>
    </div>
</nav>