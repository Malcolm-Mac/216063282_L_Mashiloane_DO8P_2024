@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4 wrapper">
            <div>
                <div class="text-center mb-4">
                    <img src="https://res.cloudinary.com/droskhnig/image/upload/v1728816687/pngegg_zhzelk.png" alt="logo" class="img-fluid w-100" />

                    <div>
                        <h4>Reset Password</h4>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3 col-12">
                        <label for="email" class="form-label">Email address</label>
                        <input id="email" type="email" placeholder="Enter your email address"
                            class="form-control form-control-lg border border-primary
                            @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 text-center">
                        @if (Route::has('login'))
                            <a class="link-offset-2 link-underline link-underline-opacity-0"
                                href="{{ route('login') }}">
                                <p>{{ __('Back to Login') }}</p>
                            </a>
                        @endif
                    </div>

                    <div class="text-center mb-0">
                        <div class="d-grid gap-2 col-10 mx-auto">
                            <button type="submit" class="btn btn-primary">
                                {{ __('SEND PASSWORD RESET LINK') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
