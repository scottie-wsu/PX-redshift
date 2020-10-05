
<nav class="navbar navbar-expand-md" >
	<div class="container">
	<ul class="navbar-nav ml-auto" >
<li class="nav-item">

	<a class="nav-link" href="{{ route('home') }}">{{ __(' Return to home') }}</a>
</li>
				
<li class="nav-item">
		<a class="nav-link" href="{{ route('MyAccount') }}">{{ __(' My Account') }}</a>

</li>

<li class="nav-item">
	<a class="nav-link" href="{{ route('history') }}">{{ __(' History') }}</a>
</li>

<li class="nav-item">

					<a class="nav-link" href="{{ route('logout') }}"
					   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
						{{ __('Logout') }}
					</a>
				</li>
</ul>
</div>

</nav>
