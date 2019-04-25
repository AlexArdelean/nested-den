<div class="header-banner">

  <ul class="sort-by">
    <li>
      <a id="sidepanel-link" class="navbar-toggler" href="#sidepanel" >☰</a> <!-- temp: sidepanel-link openbtn menu -->
    </li>
    <li class="">
      <button type="button" class="btn btn-outline-primary btn-sm">Hot</button>
    </li>
    <li class="">
      <button type="button" class="btn btn-outline-primary btn-sm">New</button>
    </li>
    <li class="">
      <button type="button" class="btn btn-outline-primary btn-sm">Top</button>
    </li>
    <li class="">
      <button type="button" class="btn btn-outline-primary btn-sm">Share a link</button>
    </li>
    <li class="">
      @if(isset($nestling))
        <a href="{{ route( 'posts.nestling.create', ['nest' => $nest, 'nestling' => $nestling->title] ) }}">
      @else
        <a href="{{ route( 'posts.create', ['nest' => isset($nest) ? $nest : 'Whatever'] ) }}">
      @endif
        <button type="button" class="btn btn-outline-primary btn-sm">Discuss</button></a>
    </li>
  </ul>
    <ul class="account-info">
      @if(Auth::user() !== null)
        <li>
          <a href="{{ route('users.show', Auth::user()->username) }}">{{Auth::user()->username}}</a>
        </li>
         |
        <li>
          0
        </li>
         |
        <li>
          0
        </li>
         |
        <li>
          Mail
        </li>
        <li>
          Settings
        </li>
         |
        <li>
          <a href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

              @csrf
          </form>
        </li>
      @else
        <li>
          <a href="{{route('login')}}">Log in</a>
        </li>
         | 
        <li>
          <a href="{{route('register')}}">Register</a>
        </li>
      @endif
    </ul>
</div>