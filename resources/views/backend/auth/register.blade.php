@include('backend.auth.includes.header',['title' =>'Register'])

    <div class="main-wrapper login-body">
      <div class="login-wrapper">
       @livewire('backend.auth.register-form')
      </div>
    </div>

@include('backend.auth.includes.footer')