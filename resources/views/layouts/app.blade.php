<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title', 'RedShift')</title>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">


	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>
	<script src="{{ asset('js/table.js') }}" defer></script>
	<script src="{{ asset('js/noty.min.js') }}" defer></script>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/table.css') }}" rel="stylesheet">

	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{ asset('images/icons/favicon.ico') }}"/>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/iconic/css/material-design-iconic-font.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/animsition/css/animsition.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/select2/select2.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">

	    <!-- External table scripts and css-->

   <script src="https://code.jquery.com/jquery-3.5.1.js" defer></script>
   <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" defer></script>
   <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js" defer></script>
   <script src="{{ asset('vendor/RowGroup-1.1.2/js/dataTables.rowGroup.min.js') }}" defer></script>
	 
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" defer>
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/RowGroup-1.1.2/css/rowGroup.bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" defer>

	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/noty.css') }}">

	<!--===============================================================================================-->

	<link  href="{{ asset('vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">
	<link  href="{{ asset('vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
	<!-- Font special for pages-->
	<link  href="{{ asset('https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i') }}" rel="stylesheet">

	<!-- Vendor CSS-->
	<link  href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
	<link  href="{{ asset('vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">
	<link  href="{{ asset('vendor/lightbox2-2.11.3/css/lightbox.css') }}" rel="stylesheet" media="all"/>

</head>
<style>
	nav-items{
		font-size: 15px;
	}
	.footer {
		position: fixed;
		left: 0;
		bottom: 0;
		width: 100%;
		height:2em;
		background-color: black;
		color: white;
		text-align: center;
	}
</style>


<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" >
	<div class="container">
		<a class="nav-link" href="{{ route('home') }}"><h2 id="redshift" style="color:red">Red</h2><h2 id="redshiftEstimator">shift</h2></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<!-- Left Side Of Navbar -->
			<ul class="navbar-nav mr-auto">

			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="navbar-nav ml-auto" >
				<!-- Authentication Links -->

				@php
					use App\User;
					use Illuminate\Support\Facades\Auth;
					use Illuminate\Support\Facades\DB;

					//admin check logic
					$user = Auth::user();
					$check = User::select('level')->where('id', $user->id)->get();
					$userChecker = $check[0]->level;

					//jobs processing check logic
					$jobCheck = DB::table('redshifts')->where('status', 'PROCESSING')->orWhere('status', 'SUBMITTED')->exists();

					//return ($userChecker == 1);
				@endphp

				@if($jobCheck == true)
					<li class="nav-item">
						<b><a class="nav-link" href="{{ route('progress') }}">{{ __('Calculation progress') }}</a></b>
					</li>
				@endif

				@if($userChecker==1)
					<li class="nav-item">
						<a class="nav-link" href="{{ backpack_url('/') }}">{{ __('Admin Panel') }}</a>
					</li>
				@endif


				<li class="nav-item">
					<a class="nav-link" href="{{ route('history') }}">{{ __('History') }}</a>

				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('MyAccount') }}">{{ __('My Account') }}</a>

				</li>
				<li class="nav-item">

					<a class="nav-link" href="{{ route('logout') }}"
					   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
						{{ __('Logout') }}
					</a>

					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>

				</li>

			</ul>
		</div>
	</div>
</nav>

<body>
@yield('content')



<!--===============================================================================================-->
<script src="{{ asset('vendor/jquery/jquery-3.2.1.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/bootstrap/js/popper.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('vendor/lightbox2-2.11.3/js/lightbox.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>



<!-- Main JS-->
<script src="{{ asset('js/global.js') }}"></script>
</body>
<div class="footer">
	<p>Powered in part by <a href="https://backpackforlaravel.com/">Backpack for Laravel</a></p>
</div>
</html>

