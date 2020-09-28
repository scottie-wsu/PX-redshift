@extends('layouts.app_boot')
@section('title','My Account')
@section('after_styles')
	<style media="screen">
		.backpack-profile-form .required::after {
			content: ' *';
			color: red;
		}
		.noty_theme__light{
			color: white;
		}
	</style>
@endsection



@section('header')
@php
	header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1

@endphp

@endsection

@section('content')




	<script src="{{ asset('js/noty.min.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/noty.css') }}">





	<head>
		<style>
			.btn_upload {
			//background-color: Blue;
				border: 1px solid black;
				color: White;
				text-align: center;
				padding-left: 20px;
				padding-right: 20px;
				margin-left: 1px;
				font-size: 16px;
				opacity: 0.6;
				transition: 0.3s;
			}
			.btn_upload:hover {opacity: 0.9}
			h2 {
				display: inline-block;
			}
			input[type='checkbox'] {
				float: left;
				width: 20px;
			}
			input[type='checkbox'] + label {
				display: block;
				width: 10%;
			}


		</style>
	</head>
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
						$user = Auth::user();
						$check = User::select('level')->where('id', $user->id)->get();
						$userChecker = $check[0]->level;

						//return ($userChecker == 1);
					@endphp

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

@php $userForm = Auth::user() @endphp
	<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
		<div class="wrapper" style="width:70%;">
			<div class="card card-2">
				<div class="card-body" >
				<h3>My Account</h3>
					@if (session('success'))
						<script>
							new Noty({
								type: 'success',
								text: 'Account information updated successfully.',
								layout: 'topCenter',
								theme: 'light',
								closeWith: ['button', 'click']
							}).show();
						</script>

					@endif
					


					@if ($errors->count())
						<br>
						<div class="col-lg-8">
							<div class="alert alert-danger">
								<ul class="mb-1">
									@foreach ($errors->all() as $e)
										<li>{{ $e }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					@endif
				</div>




				{{-- UPDATE INFO FORM --}}
				<div class="col-md-12">
					<form class="form" action="{{ route('MyAccountUpdate') }}" method="post">

						{!! csrf_field() !!}

						<div class="card padding-10">
							<div class="card-header">
								<b>Update Account Info</b>

							</div>

							<div class="card-body backpack-profile-form bold-labels">
								<div class="row">
									<div class="col-sm-4 form-group">
										@php
											$label = 'Name';
											$field = 'name';
										@endphp
										<label class="required">{{ $label }}</label>
										<input required class="form-control" type="text" name="{{ $field }}" value="{{ $userForm->$field }}">
									</div>

									<div class="col-sm-4 form-group">
										@php
											$label = 'Email';
											$field = 'email';
										@endphp
										<label class="required">{{ $label }}</label>
										<input required class="form-control" type="email" name="{{ $field }}" value="{{ $userForm->$field }}">
									</div>

									<div class="col-sm-4 form-group">
										@php
											$label = 'Institution';
											$field = 'institution';
										@endphp
										<label class="required">{{ $label }}</label>
										<input required class="form-control" type="text" name="{{ $field }}" value="{{ $userForm->$field }}">
									</div>

								</div>
							</div>

							<div class="card-footer">
								<button type="submit" class="btn btn-success"><i class="la la-save"></i> Save changes</button>
							</div>
						</div>

					</form>
				</div>
				<br>
				{{-- CHANGE PASSWORD FORM --}}
				<div class="col-lg-12">
					<form class="form" action="{{ route('MyAccountPassword') }}" method="post">

						{!! csrf_field() !!}

						<div class="card padding-10">

							<div class="card-header">
								<b>Change password</b>
							</div>

							<div class="card-body backpack-profile-form bold-labels">
								<div class="row">
									<div class="col-md-4 form-group">
										@php
											$label = 'Old password';
											$field = 'old_password';
										@endphp
										<label class="required">{{ $label }}</label>
										<input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
									</div>

									<div class="col-md-4 form-group">
										@php
											$label = 'New password';
											$field = 'new_password';
										@endphp
										<label class="required">{{ $label }}</label>
										<input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
									</div>

									<div class="col-md-4 form-group">
										@php
											$label = 'Confirm password';
											$field = 'confirm_password';
										@endphp
										<label class="required">{{ $label }}</label>
										<input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
									</div>
								</div>
							</div>

							<div class="card-footer">
								<button type="submit" class="btn btn-success"><i class="la la-save"></i>  Change password </button>
							</div>
						</div>
					</form>
				</div>
				<br>




			</div>
		</div>
	</div>

@endsection
