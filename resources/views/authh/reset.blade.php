@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
          <div class="col-md-12 text-center">
            <img style="
            max-width: 200px;
            width: 100%;
            margin: auto;
            " src="{{asset('/logos/merco.png')}}" alt="">
          </div>
          <br>
            <div class="col-md-12 text-center">
              <h1>Merco</h1>
            </div>

        <div class="col-md-8 down">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ url('/password') }}" oninput='up2.setCustomValidity(up2.value != password.value ? "Passwords do not match." : "")'>
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">


                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required >

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
                                <input id="password2" type="password" class="form-control" name="up2" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                 <input type=submit value="{{ __('Reset Password') }}">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
