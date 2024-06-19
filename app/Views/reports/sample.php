<style>
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
        background-image: url('<?= base_url("assets/images/csfp_logo.png"); ?>');
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
</style>


<div class="spinner spinner4"></div>