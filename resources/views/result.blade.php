@extends('layouts.app_boot')
@section('title','Result')
@section('content')


<nav class="navbar navbar-expand-lg navbar-light bg-light" >
            <div class="container">
           
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
                        @guest

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('guest') }}">{{ __('Guest') }}</a>
                            </li>
                            <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                             </li>
                                
                    
                             
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
<div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins" style="background-image: url('/images/bg1.jpg')">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body" >
                <b>The Redshift result is {{$red_result}}</b>
          </div>
            </div>
        </div>
    </div>

        	@endsection
	
