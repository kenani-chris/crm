<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In | TOYOTA KENYA CRM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">

		<!-- App css -->
		<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
		<link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<!--
		<link href="https://coderthemes.com/ubold/layouts/assets/css/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
		<link href="https://coderthemes.com/ubold/layouts/assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />
-->
		<!-- icons -->
		<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

    </head>

    <body class="loading auth-fluid-pages pb-0">

        <div class="auth-fluid">
           
           
            

             <!--Auth fluid left content -->
             <div class="auth-fluid-form-box">
                <div class="align-items-center d-flex h-100">
                    <div class="card-body">

                        <!-- Logo -->
                        <div class="auth-brand text-center text-lg-left " style="pading-bottom:100px">
                            <div class="auth-logo">
                                <a href="#" class="logo logo-dark text-center mb-5">
                                    <span class="logo-lg">
                                        <img src="{{asset('assets/images/logo.jpeg')}}" class="img-responsive" alt="TOYOTA KENYA" height="70">
                                    </span>
                                </a>
            
                                <a href="#" class="logo logo-light text-center mb-5">
                                    <span class="logo-lg">
                                        <img src="{{asset('assets/images/logo.jpeg')}}" class="img-responsive"  alt="TOYOTA KENYA" height="70">
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12"><br><br></div>


                        <!-- title-->
                        <h4 class="mt-50">Sign In</h4>
                        <p class="text-muted mb-4">Enter your email address and password to access account.</p>

                        <!-- form -->
                        @livewire('login')
                        <!-- end form-->

                        <!-- Footer-->
                        <footer class="footer footer-alt">
                            <p class="text-muted">Don't have an account? <a href="#" class="text-muted ml-1"><b>Sign Up</b></a></p>
                        </footer>

                    </div> <!-- end .card-body -->
                </div> <!-- end .align-items-center.d-flex.h-100-->
            </div>



            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <h2 class="mb-3 text-white">TOYOTA KENYA Call Center CRM</h2>
                    <p class="lead"><i class="mdi mdi-format-quote-open"></i>Everyone says Toyota is the best company in the world, but the customer doesn't care about the world. They care if we are the best in town, or not. That's what I want to be. <i class="mdi mdi-format-quote-close"></i> .. Akio Toyoda
                    </p>
                   
                </div> 
            </div>
          
        </div>
        @livewireScripts
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>

      
        <script src="{{asset('assets/js/app.min.js')}}"></script>
        
    </body>
</html>