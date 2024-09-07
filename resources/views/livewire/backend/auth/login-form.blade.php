<div class="container">
    <img class="img-fluid logo-dark mb-2" src="{{ asset('backend/assets/img/logo.png') }}" alt="Logo" />
    <div class="loginbox">
        <div class="login-right">
            <div class="login-right-wrap">
                <h1>Login</h1>
                <p class="account-subtitle">Access to our dashboard</p>

                <form wire:submit.prevent="login">
                    <div class="form-group">
                        <label class="form-control-label">Email Address</label>
                        <input class="form-control" type="text" wire:model='email' />
                           @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Password</label>
                        <div class="pass-group">
                            <input class="form-control" type="password" wire:model='password'/>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="cb1" wire:model='remember' />
                                    <label class="custom-control-label" for="cb1">Remember me</label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a class="forgot-link" href="forgot-password.html">Forgot Password ?</a>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-lg btn-block btn-primary w-100" type="submit">
                        Login
                    </button>


                    <div class="text-center dont-have">
                        Don't have an account yet?
                        <a href="{{ route('register') }}">Register</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
