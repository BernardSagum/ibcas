
<!doctype html>
<html lang="en">

    
<!-- Mirrored from themesbrand.com/skote-django/layouts/auth-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Sep 2022 03:40:25 GMT -->
<head>
        
<meta charset="utf-8" />
<title>iBCAS | Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
<link rel="icon" href="<?php echo base_url('assets/images/csfp_logo.png');?>">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />
<!-- App favicon -->
<!-- <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico');?>"> -->
<!-- Bootstrap Css -->
<link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?php echo base_url('assets/css/icons.min.css');?>" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?php echo base_url('assets/css/app.min.css');?>" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="<?php echo base_url('assets/libs/toastr/build/toastr.min.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css');?>" id="app-style" rel="stylesheet" type="text/css" />


<!-- owl.carousel css -->
<link rel="stylesheet" href="<?php echo base_url('assets/libs/owl.carousel/assets/owl.carousel.min.css');?>">

<link rel="stylesheet" href="<?php echo base_url('assets/libs/owl.carousel/assets/owl.theme.default.min.css');?>">

    </head>

    <body class="auth-body-bg">
 


   
               
            <div class="container-fluid p-0">

                <div class="row g-0">
                   <div class="col-xl-9">
                        <div class="auth-full-bg pt-lg-5 p-4">
                            <div class="w-100">
                                <div class="bg-overlay"></div>
                              
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-3">

                        <div class="auth-full-page-content p-md-5 p-4">
                            <div class="w-100">
    
                                <div class="d-flex flex-column" style="margin-bottom: 0;">
                                    <div class="mb-4 mb-md-5" style="margin-bottom: 0 !important;">
                                        <a href="index.html" class="d-block auth-logo">
                                            <img src="<?php echo base_url('assets/images/csfp_logo_dark3.png');?>" alt="pic" height="250" class="auth-logo-dark">
                                            <!-- <img src="assets/images/logo-light.png" alt="" height="18" class="auth-logo-light"> -->
                                        </a>
                                        
                                    </div>
                                    <div class="my-auto">
                                        
                                        
            
                                        <div class="mt-4">
                                            
                                            <form id="frm_login">
                
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                                                </div>
                        
                                                <div class="mb-3">
                                                    <!-- <div class="float-end">
                                                        <a href="auth-recoverpw-2.html" class="text-muted">Forgot password?</a>
                                                    </div> -->
                                                    <label class="form-label">Password</label>
                                                    <div class="input-group auth-pass-inputgroup">
                                                        <input type="password" class="form-control" placeholder="Enter password" name="password" aria-label="Password" aria-describedby="password-addon">
                                                        <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                        
                                                <!-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                                    <label class="form-check-label" for="remember-check">
                                                        Remember me
                                                    </label>
                                                </div> -->
                                                
                                                <div class="mt-3 d-grid">
                                                    <button class="btn btn-primary waves-effect waves-light" id="btnsubmit" type="submit">Log In</button>
                                                    <button type="button" id="frm_loading" hidden="true" class="btn btn-primary waves-effect waves-light" disabled="disabled"><span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Logging in...</button>
                                                </div>
                    
                                                
                                                <!-- <div class="mt-4 text-center">
                                                    <h5 class="font-size-14 mb-3">Sign in with</h5>
                    
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item">
                                                            <a href="javascript::void()" class="social-list-item bg-primary text-white border-primary">
                                                                <i class="mdi mdi-facebook"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <a href="javascript::void()" class="social-list-item bg-info text-white border-info">
                                                                <i class="mdi mdi-twitter"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <a href="javascript::void()" class="social-list-item bg-danger text-white border-danger">
                                                                <i class="mdi mdi-google"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div> -->

                                            </form>
                                            <div class="mt-5 text-center">
                                                <!-- <p>Don't have an account ? <a href="auth-register-2.html" class="fw-medium text-primary"> Signup now </a> </p> -->
                                            </div>
                                        </div>
                                    </div>
                                    <footer class="footer" style="left: 0;background-color: #ffffff !important;color: #686060;">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12" style="text-align: center;">
                                                    <script>
                                                        document.write(new Date().getFullYear());
                                                    </script>
                                                    © City of San Fernando, Pampanga.
                                                <br>
                                                    Powered <i class="fa-solid fa-battery-bolt"></i> by City ICT Office
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </footer>
                                    <!-- <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> City of San Fernando, Pampanga <br>Designed &amp; Developed with <i class="mdi mdi-heart text-danger"></i> by the City Information and Communication Technology Office</p>
                                    </div> -->
                                </div>

<style>
.alert {
    margin: 20px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}</style>
<div class="alert alert-success text-center d-none" id="loginAlert" role="alert">
  <h4 class="alert-heading">Successfully Logged In!</h4>
  <p>You are being redirected...</p>
  <div class="spinner-border text-success" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>
<div id="loginFailAlert" class="alert alert-danger text-center d-none" role="alert">
  <h4 class="alert-heading">Login Failed!</h4>
  <p>Incorrect username or password. Please try again.</p>
</div>
<div class="position-fixed p-3" style="z-index: 1005; left:50%; transform:translate(-50%,-660%); color:aliceblue;">
                    <div role="alert" aria-live="assertive" aria-atomic="true" id="toastsuccess"
                        class="toast align-items-center fade bg-success border-0">
                        <div class="d-flex">
                            <div class="me-0 m-auto ms-2"><i class="mdi mdi-information text-white fs-4"></i></div>
                            <div class="toast-body text-white" id="tstsuccess">
                                <strong>Sucessfully saved. </strong> 
                            </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>

        </div>
        <div class="position-fixed p-3" style="z-index: 1005; top: 11%; left:50%; transform:translate(-50%,-65%);color:aliceblue;">
            <div role="alert" aria-live="assertive" aria-atomic="true" id="toasterror"
                class="toast align-items-center fade  bg-danger border-0 mb-4">
                <div class="d-flex">
                    <div class="me-0 m-auto ms-2"><i class="mdi mdi-close-circle text-white  fs-4"></i></div>
                    <div class="toast-body text-white " id="tsterror">
                        <strong>Error</strong> 
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
            <div class="position-fixed p-3" style="z-index: 1005; left:50%; transform:translate(-50%,-65%);color:aliceblue;">
            <div role="alert" aria-live="assertive" aria-atomic="true" id="toastwarning"
                class="toast align-items-center fade  bg-warning  border-0 mb-4">
                <div class="d-flex">
                    <div class="me-0 m-auto ms-2"><i class="mdi mdi-information text-white fs-4"></i></div>
                    <div class="toast-body text-white" id="tstwarning">
                        <strong>Unable to save changes.</strong> 
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>

        <!-- JAVASCRIPT -->
        <script>
            var base_url = "<?php echo base_url();?>";
        </script>
        <script src="<?php echo base_url('assets/libs/jquery/jquery.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/metismenu/metisMenu.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/simplebar/simplebar.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/node-waves/waves.min.js')?>"></script>
        <script src="<?php echo base_url('assets/js/pages/axios.min.js');?>"></script>
        <!-- owl.carousel js -->
        <script src="<?php echo base_url('assets/libs/owl.carousel/owl.carousel.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/toastr/build/toastr.min.js')?>"></script>
        <!-- auth-2-carousel init -->
        <script src="<?php echo base_url('assets/js/pages/auth-2-carousel.init.js')?>"></script>
        <script src="<?php echo base_url('assets/js/pages/popper.min.js');?>"></script>
        <!-- App js -->
        <script src="<?php echo base_url('assets/js/app.js')?>"></script>
        <script src="<?php echo base_url('assets/js/login.js')?>"></script>

    </body>

<!-- Mirrored from themesbrand.com/skote-django/layouts/auth-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Sep 2022 03:40:26 GMT -->
</html>
