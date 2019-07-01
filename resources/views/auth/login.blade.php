@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <section class="w-auth-form" >
        <form class="w-form" method="POST" action="{{ route('login') }}">
            <header>
                <h3>Login</h3>
                <hr>
            </header>
            <div>
                <div class="container" >
                    @error('email')
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Email ou senham incorretos.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="userName">Email</label>
                    <input type="email" name="email" class="form-control w-require @error('email') w-required-empty @enderror" id="userName" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email de acesso">
                </div>
                <div class="form-group">
                    <label for="userName">Senha</label>
                    <input type="password" name="password" class="form-control w-require @error('email') w-required-empty @enderror" id="userName" required autocomplete="current-password" placeholder="Senha de acesso">
                </div>
                <!-- <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> -->

                <!-- <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> -->
            </div>
            @csrf
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn bt-sm btn-outline-primary">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection
