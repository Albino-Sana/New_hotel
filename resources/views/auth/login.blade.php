<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
      DAT Hotelaria - Login
    </title>
    @include('components.css')
  
</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0">
    </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Iniciar sessão</h4>
                                    <p class="mb-0">Digite seu e-mail e senha para fazer login</p>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('login') }}" role="form">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" :value="old('email')" required autofocus>
                                            @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Palavra passe" required>
                                            @error('password')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Lembrar-me</label>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Entrar</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('{{ asset('assets/img/login.jpg') }}'); background-size: cover;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">
                                    "A sua experiência começa aqui"
                                </h4>
                                <p class="text-white position-relative">
                                    Gestão hoteleira com excelência – porque cada detalhe conta.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>
    <!--   Core JS Files   -->
   @include('components.js')
  
</html>
