@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100 position-relative">
    <div class="row justify-content-center w-75">
        <div class="col-md-6 col-lg-5 wrapper">
            <div>
                <div class="text-center mb-4">
                    <img src="https://res.cloudinary.com/droskhnig/image/upload/v1728816687/pngegg_zhzelk.png"
                        alt="logo" class="img-fluid w-100" />
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3 col-12">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" placeholder="Enter your password"
                            class="form-control form-control-lg border border-primary w-100 @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- <div class="mb-3 text-center">
                        @if (Route::has('password.request'))
                            <a class="link-offset-2 link-underline link-underline-opacity-0"
                                href="{{ route('password.request') }}">
                                <p>{{ __('Forgot Your Password?') }}</p>
                            </a>
                        @endif
                    </div> --}}

                    <div class="text-center mb-0">
                        <div class="d-grid gap-2 col-5 mx-auto">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
