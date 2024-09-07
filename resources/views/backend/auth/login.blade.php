@include('backend.auth.includes.header',['title' =>'Login'])
    <div class="main-wrapper login-body">
      <div class="login-wrapper">
       @livewire('backend.auth.login-form')
      </div>
    </div>

   @include('backend.auth.includes.footer')