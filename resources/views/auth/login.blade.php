@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card-panel hoverable">
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                <div class="row">
                    <ul><li class="collection-header"><h4>{{ __('Login') }}</h4></li></ul>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="email" type="email" class="validate {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                        <label for="email">{{ __('E-Mail Address') }}</label>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="password" class="validate {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        <label for="password">{{ __('Password') }}</label>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                        @endif
                    </div>
                </div>
                <p>
                    <label>
                        <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>
                    {{ __('Remember Me') }}
                </span>
                    </label>
                </p>

                <button type="submit" class="waves-effect waves-light btn">
                    {{ __('Login') }}
                </button>

                <a class="waves-effect waves-light btn" href="{{ route('loginSocial', 'facebook') }}">
                    {{ __('Ingresa con Facebook') }}
                </a>

                <a class="waves-effect waves-light btn" href="{{ route('loginSocial', 'google') }}">
                    {{ __('Ingresa con Google') }}
                </a>

                <a class="waves-effect waves-light btn" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </form>
        </div>
    </div>
@endsection
