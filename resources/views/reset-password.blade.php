<!DOCTYPE html>
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
  
            
                <div class="card-header text-center bg-primary text-white ">
                    <h4 class="mb-0">Reset Password</h4>
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
                    <form method="POST" action="{{ route('reset-password.update') }}" >
                        @csrf

                        <div class="mb-3">
                            <label for="cur_password" class="form-label">Current Password</label>
                            <input type="password" name="cur_password" id="cur_password" value="{{ old('cur_password') }}"
                                   class="form-control @error('cur_password') is-invalid @enderror" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label d-flex justify-content-between">
                                New Password
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none">
                                        Forgot?
                                    </a>
                                @endif
                            </label>
                            <input type="password" name="new_password" id="new_password"
                                   class="form-control @error('new_password') is-invalid @enderror" required>
                                    <div class="input-group-prepend">
                                    <div class="input-group-text"> 
                                    <span id="icon_click" class="fas fa-eye text-info"></span>
                                    </div>
                                    </div>
                        </div>

                       
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary login">Reset Password</button>
                        </div>
                    </form>

                    
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
<script src="/js/user/reset-password.js"></script>
</body>

</html>