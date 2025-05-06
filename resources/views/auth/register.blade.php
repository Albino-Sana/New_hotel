
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Argon Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="">
  <!-- Navbar -->
  <!-- End Navbar -->
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"                                 style="background-image: url('{{ asset('assets/img/login.jpg') }}'); background-size: cover;">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Bem-vindo!</h1>
            <p class="text-lead text-white">"Crie a sua conta com os seus dados para iniciar sessão e embarcar numa jornada incrível na gestão do seu hotel."</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5> Registre-se</h5>
            </div>
          
            <div class="card-body">
            <form role="form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                      <input type="text" name="name" class="form-control form-control-lg" placeholder="Name" value="{{ old('name') }}" required autofocus>
                      @error('name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                      @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                      <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" required>
                      @error('email')
                        <span class="text-danger text-sm">{{ $message }}</span>
                      @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                      <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required autocomplete="new-password">
                      @error('password')
                        <span class="text-danger text-sm">{{ $message }}</span>
                      @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                      <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm Password" required autocomplete="new-password">
                      @error('password_confirmation')
                        <span class="text-danger text-sm">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-lg bg-gradient-primary w-100 mt-4 mb-0">Registrar</button>
                    </div>
                </form>
              </div>
              <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <p class="mb-4 text-sm mx-auto">
                 Ja tem uma conta?
                  <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Iniciar sessão</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
          <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-dribbble"></span>
          </a>
          <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-twitter"></span>
          </a>
          <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-instagram"></span>
          </a>
          <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-pinterest"></span>
          </a>
          <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
            <span class="text-lg fab fa-github"></span>
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> DAT Hotelaria Todos os direitos reservados 
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
@include('components.js')

</body>

</html>