@extends('layouts.app_boot')
@section('title','My Account')




@section('header')

@endsection

@section('content')



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
			body {
				overflow: hidden;
			}

		</style>
	</head>
	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" >
		<div class="container">
			<a class="nav-link" href="{{ route('home') }}"><h2 id="redshift" style="color:red">Red</h2><h2 id="redshiftEstimator">Shift</h2></a>
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

						<a class="nav-link" href="{{ route('logout') }}"
						   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
							{{ __('Logout') }}
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>

					</li>
					<h4 class="nav-link"> {{ Auth::user()->name }} </h4>

				</ul>
			</div>
		</div>
	</nav>


	<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url({{ asset('images/bg1.jpg') }})">
		<div class="wrapper" style="width:70%">
			<div class="card card-4">
				<div class="card-body" >
					<form name="form1" method="POST" action="{{ route('MyAccountUpdate') }}" style="align-items:center;">
						{!! csrf_field() !!}
						<div class="card-body backpack-profile-form bold-labels">
							<div class="row">
								<div class="card-body backpack-profile-form bold-labels">
									<div class="row">
										<div class="col-md-6 form-group">
											@php
												if(isset($user1)){
													$userForm = $user1;
												}
												else{
													$userForm = Auth::user();
												}
												$label = 'Name';
												$field = 'name';
											@endphp
											<label class="required">{{ $label }}</label>
											<input required class="form-control" type="text" name="{{ $field }}" value="{{ $userForm->$field }}">
										</div>

										<div class="col-md-6 form-group">
											@php
												$label = 'Email';
												$field = 'email';
											@endphp
											<label class="required">{{ $label }}</label>
											<input required class="form-control" type="text" name="{{ $field }}" value="{{ $userForm->$field }}">
										</div>

										<div class="col-md-6 form-group">
											@php
												$label = 'Institution';
												$field = 'institution';
											@endphp
											<label class="required">{{ $label }}</label>
											<input required class="form-control" type="text" name="{{ $field }}" value="{{ $userForm->$field }}">
										</div>

										<input type="hidden" name="id" id="id" value="{{ auth()->id() }}">

										<button class ="btn_upload btn--radius-2 btn--blue" type="submit">Update details</button>


										</div>

									</div>
								</div>
							</div>
					</form>
				</div>



				{{-- CHANGE PASSWORD FORM --}}
				<div class="col-lg-8">
					<form class="form" action="{{ route('backpack.account.password') }}" method="post">

						{!! csrf_field() !!}

						<div class="card padding-10">

							<div class="card-header">
								{{ trans('backpack::base.change_password') }}
							</div>

							<div class="card-body backpack-profile-form bold-labels">
								<div class="row">
									<div class="col-md-4 form-group">
										@php
											$label = trans('backpack::base.old_password');
											$field = 'old_password';
										@endphp
										<label class="required">{{ $label }}</label>
										<input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
									</div>

									<div class="col-md-4 form-group">
										@php
											$label = trans('backpack::base.new_password');
											$field = 'new_password';
										@endphp
										<label class="required">{{ $label }}</label>
										<input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
									</div>

									<div class="col-md-4 form-group">
										@php
											$label = trans('backpack::base.confirm_password');
											$field = 'confirm_password';
										@endphp
										<label class="required">{{ $label }}</label>
										<input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
									</div>
								</div>
							</div>

							<div class="card-footer">
								<button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.change_password') }}</button>
								<a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
							</div>

						</div>

					</form>
				</div>




			</div>
		</div>
	</div>

@endsection
