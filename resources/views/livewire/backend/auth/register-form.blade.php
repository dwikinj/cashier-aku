<div class="container">
    <img
      class="img-fluid logo-dark mb-2"
      src="{{asset('backend/assets/img/logo.png')}}"
      alt="Logo"
    />
    <div class="loginbox">
      <div class="login-right">
        <div class="login-right-wrap">
          <h1>Register</h1>
          <p class="account-subtitle">Access to our dashboard</p>

          <form wire:submit.prevent="submit">
            <div class="form-group">
              <label class="form-control-label">Name</label>
              <input class="form-control" type="text" wire:model='name' />
              @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-control-label">Email Address</label>
              <input class="form-control" type="text" wire:model='email' />
              @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-control-label">Password</label>
              <input class="form-control" type="password" wire:model='password'/>
              @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <label class="form-control-label">Confirm Password</label>
              <input class="form-control" type="password" wire:model='password_confirmation'/>
            </div>
            <div class="form-group mb-0">
              <button
                class="btn btn-lg btn-block btn-primary w-100"
                type="submit"
              >
                Register
              </button>
            </div>
          </form>

          <div class="text-center dont-have">
            Already have an account? <a href="{{route('login')}}">Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>