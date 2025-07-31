<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bootstrap 5 - Login Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" />
</head>

<body class="main-bg">
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<div class="container  min-vh-75 d-flex align-items-center justify-content-center" style="margin-top:50px!important">
    <div class="row w-100">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow rounded-4">
             <div class="card-header text-center mt-4 mb-2 rounded-top-4">
                   <img src="https://www.ultralabs.co.in/public/images/logo.png"></img>
                </div>
            
                <div class="card-header text-center bg-primary text-white ">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
                    <form method="POST" action="{{ route('verify-login') }}" >
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label d-flex justify-content-between">
                                Password
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none">
                                        Forgot?
                                    </a>
                                @endif
                            </label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary login">Login</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted small">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="text-primary small">Register</a><br>
                         <span class="text-muted small">Want to reset password?</span>
                        <a href="{{ route('forgot-password') }}" class="text-primary small">Forgot Password</a><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

</body>

</html>