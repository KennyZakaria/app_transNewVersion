<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="robots" content="noindex">
        <title>TransExpress.ma - Login</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../assets/img/transexpress-logo-white.png" />
        <!-- Font Awesome icons (free version)-->
        <script src="../js/fontawesome.com_releases_v6.3.0_js_all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- login Section-->
        <section class="page-section bg-primary mb-0" id="about">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                    	<div class="login-page">
                    		<form method="POST" action="{{ route('login') }}">
                                @csrf
							    <a href="index.html"><img src="../assets/img/transexpress-logo-login.svg" width="80%"></a>

							    <div>
							      <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email address">
							    </div>
							    <div>
							      <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
							    </div>

							    <div class="checkbox mb-3">
							      <label>
							        <input type="checkbox" value="remember-me"> Remember me
							      </label>
							    </div>
							    <button class="w-100 btn btn-lg btn-primary" type="submit">Connexion</button>
							    <p><a href="forget-password.html">Forgot password ?</a></p>
							    <p class="text-muted">Don’t have account ? <a href="register.html" class="create-acc">Create account</a></p>
							  </form>
                    	</div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Bootstrap core JS-->
        <script src="../js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="../js/scripts.js"></script>
    </body>
</html>

