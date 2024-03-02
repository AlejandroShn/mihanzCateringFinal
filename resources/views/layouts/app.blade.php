<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/core@6.1.10/main.min.css" />
  <!-- FullCalendar DayGrid plugin CSS -->
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/daygrid@6.1.10/main.min.css" />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
  <title>Mihanz Catering</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('/') }}">Mihanz Catering</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="nav nav-tabs ">
          @guest
          <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">{{ __('Home') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('menus') ? 'active' : '' }}" href="{{ url('/menus') }}">{{ __('Menu') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('services') ? 'active' : '' }}" href="{{ url('/services') }}">{{ __('Services') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('themes') ? 'active' : '' }}" href="{{ url('/themes') }}">{{ __('Theme') }}</a>
          </li>
          @if (Route::has('login'))
          <li class="nav-item">
            <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          @endif

          @if (Route::has('register'))
          {{-- <li class="nav-item">
            <a class="nav-link {{ Request::is('register') ? 'active' : '' }}" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li> --}}
          @endif

          @else
          <li class="nav-item">
            @if (auth()->check())
            @if (auth()->user()->hasRole('admin'))
            <a class="nav-link {{ Request::is('admin-dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
            @else
            <a class="nav-link {{ Request::is('user-dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
            @endif
          </li>
          @endif
          <li class="nav-item">
            <a class="nav-link {{ Request::is('menus') ? 'active' : '' }}" href="{{ url('/menus') }}">{{ __('Menu') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('services') ? 'active' : '' }}" href="{{ url('/services') }}">{{ __('Services') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('themes') ? 'active' : '' }}" href="{{ url('/themes') }}">{{ __('Theme') }}</a>
          </li>
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              {{-- <a class="dropdown-item" href="">
                {{ __('User Profile') }}
              </a> --}}
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
  <main>

    @yield('content')

  </main>






  <!-- footer -->

  <footer>
    <h1 class="display-1 "  style="font-family: 'Playfair Display', serif;">
      Mihanz Catering
    </h1>

    <ul>
      <li>
        <label for="getInTouch" class="h3">Get in touch</label>
        <br /><a href="https://www.facebook.com/profile.php?id=100066545202436" target="_blank" rel="noreferrer">
          <FaFacebook />Facebook
        </a>
      </li>
      <li>
        <label for="Contact" class="h3">Contact</label><br />
        <a href="#">
          <FaMobileScreen />0926-563-1143 <br />
          <FaMobileScreen /> 0916-412-2250
        </a>
      </li>
      <li>
        <label for="Location" class="h3">Location</label> <br />
        <a href="https://goo.gl/maps/eYMwkLUwLh3tVrsLA" target="_blank" rel="noreferrer">
          <FaMapLocationDot /> Calderon St. Subic
          Baliwag, Bulacan
        </a>

      </li>
     
    </ul>

  </footer>

  <script src="https://unpkg.com/moment@2.29.1/moment.min.js"></script>
  <script src="https://unpkg.com/@fullcalendar/core@6.1.10/main.min.js"></script>
  <script src="https://unpkg.com/@fullcalendar/daygrid@6.1.10/main.min.js"></script>
</body>

</html>