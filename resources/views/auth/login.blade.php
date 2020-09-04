@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <section class="w-auth-form py-5" >
        <form class="w-form border border-dark" method="POST" action="{{ route('login') }}">
            <header>
                <h3>Login</h3>
                <hr class="border-dark" >
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
                <button type="submit" class="btn bt-sm btn-outline-dark">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link text-dark" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection
