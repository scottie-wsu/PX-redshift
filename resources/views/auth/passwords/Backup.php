
@extends('layouts.app')
@section('title','Login')

	
@section('content')

    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color:#002855; color:white">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title','Register')
    



@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="background-color:#002855; color:white">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                            <div class="form-group row">
                            <label for="Institution" class="col-md-4 col-form-label text-md-right">{{ __('Institution') }}</label>

                            <div class="col-md-6">
                                <input id="institution" type="text" class="form-control @error('institution') is-invalid @enderror" name="institution" value="{{ old('institution') }}" required autocomplete="institution">

                                @error('institution')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row ">
                          <div class="col-md-8 offset-md-5" >
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>  
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


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
    
</head>
<body style="background-color:#242423;">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="background-color:#0466c8!important; ">
            <div class="container">
                <!-- <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'RedShift') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button> -->

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
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                             </li>
                                
                    
                             
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
<main class="py-4">
            
   <div class="container-fluid" >
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="background-color:#002855; color:white">
                <div class="card-header">{{ __('Redshift Estimator') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('calculation') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="assigned_calc_ID" class="col-md-4 col-form-label text-md-right">{{ __('Calculation ID') }}</label>

                            <div class="col-md-6">
                                <input id="assigned_calc_ID" type="text" class="form-control @error('assigned_calc_ID') is-invalid @enderror" name="assigned_calc_ID" value="{{ old('assigned_calc_ID') }}" required autocomplete="assigned_calc_ID" autofocus>

                                @error('assigned_calc_ID')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="optical_u" class="col-md-4 col-form-label text-md-right">{{ __('Optical u') }}</label>

                            <div class="col-md-2">
                                <input id="optical_u" type="number" class="form-control @error('optical_u') is-invalid @enderror" name="optical_u" value="{{ old('optical_u') }}" required autocomplete="optical_u" autofocus>

                                @error('optical_u')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <!-- </div>
                        <div class="form-group row"> -->
                            

                            <div class="col-md-2">
                                <input id="optical_g" type="number" class="form-control @error('c_optical_gid') is-invalid @enderror" name="optical_g" value="{{ old('optical_g') }}" required autocomplete="optical_g" autofocus>
<label for="optical_g" class="row-md-1">{{ __(' Optical g') }}</label>
                                @error('optical_g')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          <!-- </div>
                        <div class="form-group row">  -->
                            

                            <div class="col-md-2">
                                <input id="optical_r" type="number" class="form-control @error('optical_r') is-invalid @enderror" name="optical_r" value="{{ old('optical_r') }}" required autocomplete="optical_r" autofocus>
<label for="optical_r" class="row-md-1">{{ __('Optical r') }}</label>
                                @error('optical_r')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                         </div>
                        <div class="form-group row"> 
                          
 <label for="optical_i" class=" col-md-4 col-form-label text-md-right">{{ __('Optical i') }}</label>
                            <div class="col-md-2">
                                <input id="optical_i" type="number" class="form-control @error('optical_i') is-invalid @enderror" name="optical_i" value="{{ old('optical_i') }}" required autocomplete="optical_i" autofocus>

                                @error('optical_i')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <!-- </div>
                        <div class="form-group row"> -->
                          

                            <div class="col-md-2">
                                <input id="optical_z" type="number" class="form-control @error('optical_z') is-invalid @enderror" name="optical_z" value="{{ old('optical_z') }}" required autocomplete="optical_z" autofocus>
  <label for="optical_z" class="row-md-2">{{ __('Optical z') }}</label>
                                @error('c_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="infrared_three_six" class="col-md-4 col-form-label text-md-right">{{ __('Infrared 3.6') }}</label>

                            <div class="col-md-2">
                                <input id="infrared_three_six" type="number" class="form-control @error('infrared_three_six') is-invalid @enderror" name="infrared_three_six" value="{{ old('infrared_three_six') }}" required autocomplete="infrared_three_six" autofocus>

                                @error('infrared_three_six')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <!-- </div>
                        <div class="form-group row"> -->
                           

                            <div class="col-md-2">
                                <input id="infrared_four_five"  type="number" class="form-control @error('infrared_four_five') is-invalid @enderror" name="infrared_four_five" value="{{ old('infrared_four_five') }}" required autocomplete="infrared_four_five" autofocus>
 <label for="infrared_four_five" class="row-md-2">{{ __('Infrared 4.5') }}</label>
                                @error('infrared_four_five')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <!-- </div>
                        <div class="form-group row"> -->
                          

                            <div class="col-md-2">
                                <input id="infrared_five_eight" type="number" class="form-control @error('infrared_five_eight') is-invalid @enderror" name="infrared_five_eight" value="{{ old('infrared_five_eight') }}" required autocomplete="infrared_five_eight" autofocus>
  <label for="infrared_five_eight" class="row-md-2">{{ __('Infrared 4.8') }}</label>
                                @error('infrared_five_eight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="infrared_eight_zero" class="col-md-4 col-form-label text-md-right">{{ __('Infrared 8.0') }}</label>

                            <div class="col-md-2">
                                <input id="infrared_eight_zero" type="number" class="form-control @error('infrared_eight_zero') is-invalid @enderror" name="infrared_eight_zero" value="{{ old('infrared_eight_zero') }}" required autocomplete="infrared_eight_zero" autofocus>

                                @error('infrared_eight_zero')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <!-- </div>
                        <div class="form-group row"> -->
                            

                            <div class="col-md-2">
                                <input id="infrared_J" type="number" class="form-control @error('infrared_J') is-invalid @enderror" name="infrared_J" value="{{ old('infrared_J') }}" required autocomplete="infrared_J" autofocus>
<label for="infrared_J" class="row-md-2">{{ __('Infrared J') }}</label>
                                @error('infrared_J')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <!-- </div>
                        <div class="form-group row"> -->
                           

                            <div class="col-md-2">
                                <input id="infrared_K" type="number" class="form-control @error('infrared_K') is-invalid @enderror" name="infrared_K" value="{{ old('infrared_K') }}" required autocomplete="infrared_K" autofocus>
 <label for="infrared_K" class="row-md-2">{{ __('Infrared K') }}</label>
                                @error('infrared_K')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="radio_one_four" class="col-md-4 col-form-label text-md-right">{{ __('Radio 1.4') }}</label>

                            <div class="col-md-6">
                                <input id="radio_one_four" type="number" class="form-control @error('radio_one_four') is-invalid @enderror" name="radio_one_four" value="{{ old('radio_one_four') }}" required autocomplete="radio_one_four" autofocus>

                                @error('radio_one_four')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                        <div class="form-group row mb-6">
                            <div class="col-md-20 offset-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Calculate') }}
                                </button>
                            </div>
                        </div>  
                        
 <div class="form-group row">
                            <label for="redshift_result" class="col-md-4 col-form-label text-md-right">{{ __('Redshift') }}</label>
<div class="col-md-6">
                                <input id="redshift_result" type="number" class="form-control @error('redshift_result') is-invalid @enderror" name="redshift_result" value="{{ old('redshift_result') }}" required autocomplete="redshift_result" autofocus>
                              
                                @error('redshift_result')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            
                        </div>
                        
                        <!-- <div class="form-group row mb-6">
                            <div class="col-md-20 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Show History') }}
                                </button>
                            </div>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    


        </main>
        
    </div>
</body>
</html>
    @guest

    @endguest
