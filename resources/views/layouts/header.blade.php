<div id="sidebar-btn">
    <i class="fas fa-bars"></i>
</div>
<nav id="sidebar-inner" class="navbar navbar-expand-lg navbar-light bg-light border-bottom" style="z-index: 99;">
    <div id="sidebar-btn-space"></div>
    <div id="parts-manager-logo">
        <a href="{{ route('home') }}">
            <img src="/storage/images/parts-manager-logo.png" alt="PARTS_MANAGER">
        </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarSupportedContent" class="collapse navbar-collapse text-right">
        <ul class="navbar-nav">
            @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            @if($isMobile)
            <li class="nav-item dropdown card">
                <a class="dropdown-item text-center" href="{{ route('users.show',['user' => auth()->user()])}}">
                    ユーザー情報
                </a>
                <a class="dropdown-item text-center" href="{{ route('users.index',['user' => auth()->user()])}}">
                    ユーザー一覧
                </a>
                <a class="dropdown-item text-center text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </li>
            @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle ml-3" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    こんにちは、{{ Auth::user()->name }}さん
                </a>
                <div class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('users.show',['user' => auth()->user()])}}" onclick="">
                        ユーザー情報
                    </a>
                    <a class="dropdown-item" href="{{ route('users.index',['user' => auth()->user()])}}" onclick="">
                        ユーザー一覧
                    </a>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            @endif
            @endguest
        </ul>
    </div>
</nav>
