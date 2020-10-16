<style>
nav-items{
font-size: 15px;
}
</style>
<nav class="navbar navbar-expand-md" >
<div class="collapse navbar-collapse" id="navbarSupportedContent">
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
        <a class="nav-link" href="{{ backpack_url('logout') }}">
            {{ trans('backpack::base.logout') }}
		</a>
		</li>
</ul>
</div>

</nav>
