@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Imagen logo -->
  <div class="row justify-content-center">
    <div class="col-md-3 logoLogin">
      <img class="img-fluid" src="{{asset('logos/merco.png')}}" alt="">
    </div>
  </div>
<br>
<br>
  <!-- Formulario login -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <!-- <div class="card-header">{{ __('Login') }}</div> -->

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label text-md-left">{{ __('Email') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-12 col-form-label text-md-left">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <a class="form-check-label" href="{{url('legal/privacidad.pdf')}}" for="remember">
                                        {{ __('Política de privacidad') }}
                                    </a>
                                </div>
                            </div>
                        </div>



                        <div class="form-group row ">
                            <div class="col-md-12 offset-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12 text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
