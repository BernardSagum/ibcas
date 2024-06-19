<!DOCTYPE html>
<html>

<head>
    <title><?= $this->renderSection('title') ?></title>
    <?= $this->include('layout/header') ?>
    <style>
        *:focus {
  outline: none;
}

                  /* Overlay styles */
                  #overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    z-index: 9999;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                  }
                  .spinners-container {
                    display: flex;
                    justify-content: center;
                  }
                  .overlay-content {
                    /*display: flex;
                    justify-content: center;
                    align-items: center;*/
                      display: flex;
                      flex-direction: column;
                      align-items: center;
                  }

                  .spinner-grow{
                        margin-right: 5px;
                  }
                   .toast-body {
                    text-align: center !important;
                   }
                .loading-text {
                  font-size: 24px;
                  font-weight: bold;
                  margin-bottom: 10px;
                  color: #ffffff;
                }
                .floating-notification {
                 /* position: fixed;
                  top: 100px;
                  right: 20px;*/
                  background-color: #50bff1;
                  color: #ffffff;
                  padding: 10px 20px;
                  border-radius: 5px;
                  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                  display: none;
                  width: fit-content;
                }
#payment-masterlist td:nth-child(1) { /* Business Control Number */
    width: 15%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

#payment-masterlist td:nth-child(2) { /* Business Name */
    width: 20%;
    overflow: hidden;
    text-overflow: ellipsis;
    /* white-space: nowrap; */
}

#payment-masterlist td:nth-child(3) { /* Tax Payer Name */
    width: 20%;
    overflow: hidden;
    text-overflow: ellipsis;
    /* white-space: nowrap; */
}

#payment-masterlist td:nth-child(4) { /* Address */
    width: 35%;
    overflow: hidden;
    text-overflow: ellipsis;
    /* white-space: nowrap; */
}

#payment-masterlist td:nth-child(5) { /* Action */
    width: 10%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.logout-alert {
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1050;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: flex;
    justify-content: center;
    padding: 10px;  
    background-color: rgba(255, 255, 255, 0.9); 
}

.alert {
    margin: 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.spinner-border2 {
    margin-top: 10px;  
}

@import url("https://fonts.googleapis.com/css?family=Source+Serif+Pro");

*,
*::before,
*::after {
    box-sizing: border-box;
}


.spinner {
    width: 5rem;
    height: 5rem;
    align-items: center;
}

.spinner.spinner4 {
    background-color: transparent;
    background-image: url('<?= base_url("assets/images/csfplogo.png"); ?>');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    background-size: 100% 100%;
    -webkit-animation: flip 1.2s ease infinite;
    animation: flip 1.2s ease infinite;
}


@-webkit-keyframes flip {
    0% {
        -webkit-transform: perspective(120px) rotateY(0deg);
        transform: perspective(120px) rotateY(0deg);
    }

    50% {
        -webkit-transform: perspective(120px) rotateY(180deg);
        transform: perspective(120px) rotateY(180deg);
    }

    100% {
        -webkit-transform: perspective(120px) rotateY(360deg);
        transform: perspective(120px) rotateY(360deg);
    }
}

@keyframes flip {
    0% {
        -webkit-transform: perspective(120px) rotateY(0deg);
        transform: perspective(120px) rotateY(0deg);
    }

    50% {
        -webkit-transform: perspective(120px) rotateY(180deg);
        transform: perspective(120px) rotateY(180deg);
    }

    100% {
        -webkit-transform: perspective(120px) rotateY(360deg);
        transform: perspective(120px) rotateY(360deg);
    }
}

.blur-effect {
    filter: blur(2px);
}


                  /* Add more custom spinner classes and colors as needed */
        </style>
</head>

<body data-topbar="dark" data-layout="horizontal" data-layout-size="boxed">
<div id="overlay">
  <div class="overlay-content">
  <div class="spinner spinner4"></div>
    <p class="loading-text" id="loading-text">LOADING</p>
    
    <!-- <div class="spinners-container">
      <div class="spinner-grow text-primary custom-spinner1" role="status"></div>
      <div class="spinner-grow text-success custom-spinner2" role="status"></div>
      <div class="spinner-grow text-danger custom-spinner3" role="status"></div>
      <div class="spinner-grow text-warning custom-spinner4" role="status"></div>
      <div class="spinner-grow text-info custom-spinner5" role="status"></div>
    </div> -->
  </div>
</div>

<div id="logoutAlert" class="logout-alert d-none">
  <div class="alert alert-success">
    <strong>Logged Out Successfully!</strong>
    <p>You have been safely logged out of your account.</p>
    <div class="spinner-border2 text-success" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</div>

    <div id="layout-wrapper">
        <?= $this->include('layout/header_topbar') ?>
        <?= $this->include('layout/header_topnav') ?>

        <?= $this->renderSection('content') ?>
    </div>
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title d-flex align-items-center px-3 py-4">

                <h5 class="m-0 me-2">Settings</h5>

                <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
            </div>

            <!-- Settings -->
            <hr class="mt-0" />
            <h6 class="text-center mb-0">Choose Layouts</h6>

            <div class="p-4">
                <div class="mb-2">
                    <img src="<?php echo base_url('assets/images/layouts/layout-1.jpg');?>" class="img-thumbnail" alt="layout images">
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                    <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                </div>

                <div class="mb-2">
                    <img src="<?php echo base_url('assets/images/layouts/layout-2.jpg');?>" class="img-thumbnail" alt="layout images">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch">
                    <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                </div>

                <div class="mb-2">
                    <img src="<?php echo base_url('assets/images/layouts/layout-3.jpg');?>" class="img-thumbnail" alt="layout images">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input theme-choice" type="checkbox" id="rtl-mode-switch">
                    <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label>
                </div>

                <div class="mb-2">
                    <img src="<?php echo base_url('assets/images/layouts/layout-4.jpg');?>" class="img-thumbnail" alt="layout images">
                </div>
                <div class="form-check form-switch mb-5">
                    <input class="form-check-input theme-choice" type="checkbox" id="dark-rtl-mode-switch">
                    <label class="form-check-label" for="dark-rtl-mode-switch">Dark RTL Mode</label>
                </div>


            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    
    <!-- JAVASCRIPT -->
    <?= $this->include('layout/footer') ?>
</body>

</html>