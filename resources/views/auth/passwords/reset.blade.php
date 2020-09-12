

@extends('layouts.app_boot')

@section('content')
	<div class="limiter" >
		<div class="container-login100" style="background-image: url('/images/bg5.jpg')">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
				<form class="login100-form validate-form" method="POST" action="{{ route('password.update') }}">
                @csrf    
              

  <span class="login100-form-title p-b-49">
						Reset Password
					</span>

					<div class="wrap-input100 validate-input m-b-23" data-validate = "Email is required">
						<span class="label-input100">{{ __('E-Mail Address') }}</span>
						<input id="email" class="input100 " type="email" name="email" placeholder="Type your email" value="{{ old('email') }}">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                        
                                @error('email')
                                
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
                    <div class="wrap-input100 validate-input m-b-23" data-validate = "Password is required">
						<span class="label-input100">{{ __('Password') }}</span>
						<input  class="input100 "   placeholder="Type your password" id="password" type="password" name="password" required autocomplete="new-password">
                        <span class="focus-input100" data-symbol="&#xf190;"></span>
                        
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
					</div>
                       <div class="wrap-input100 validate-input m-b-23" data-validate = "Password is required">
						<span class="label-input100">{{ __('Confirm Password') }}</span>
						<input  class="input100 "   placeholder="Confirm password" required autocomplete="new-password" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <span class="focus-input100" data-symbol="&#xf190;"></span>
                        
                             
					</div>
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" class="login100-form-btn">
								{{ __('Reset Password') }}
							</button>
						</div>
					</div>


				</form>
			</div>
		</div>
	</div>

	@endsection
	


