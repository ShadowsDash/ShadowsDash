<?php
require("../require/config.php");
session_start();
?>
<!--
=========================================================
* Argon Dashboard - v1.2.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright  Creative Tim (http://www.creative-tim.com)
* Coded by www.creative-tim.com
=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $_CONFIG["name"] ?> - Login</title>
  <!-- Favicon -->
  <link rel="icon" href="/assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="/assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="/assets/css/argon.css?v=1.2.0" type="text/css">
</head>

<body class="bg-default">
  <!-- Navbar -->
  <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="/">
        <img src="<?= $_CONFIG["logo_white"] ?>">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
        <div class="navbar-collapse-header">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="/">
                <img src="<?= $_CONFIG["logo_white"] ?>">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a href="<?= $_CONFIG["website"] ?>" class="nav-link">
              <span class="nav-link-inner--text">Website</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $_CONFIG["statuspage"] ?>" class="nav-link">
              <span class="nav-link-inner--text">Status page</span>
            </a>
          </li>
        </ul>
        <hr class="d-lg-none" />
        <ul class="navbar-nav align-items-lg-center ml-lg-auto">
          <li class="nav-item">
            <a class="nav-link nav-link-icon" href="<?= $_CONFIG["discordserver"] ?>" target="_blank" data-toggle="tooltip" data-original-title="Like us on Facebook">
              <i class="fab fa-discord"></i>
              <span class="nav-link-inner--text d-lg-none">Discord server</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Welcome!</h1>
              <p class="text-lead text-white">To continue, you must login! This will automatically create you an account if you do not own one.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
        <?php
        if (isset($_SESSION["error"])) {
            ?>
            <div class="alert alert-danger" role="alert">
              <strong>Error!</strong> <?= $_SESSION["error"] ?>
            </div>
            <?php
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
          ?>
          <div class="alert alert-success" role="alert">
            <strong>Success!</strong> <?= $_SESSION["success"] ?>
          </div>
          <?php
          unset($_SESSION["success"]);
      }
        ?>
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent pb-5">
              <div class="text-muted text-center mt-2 mb-3"><small>Sign in with</small></div>
              <div class="btn-wrapper text-center">
                <a href="discord" class="btn btn-neutral btn-icon" onClick="showLoading()">
                  <span class="btn-inner--icon"><img src="https://i.imgur.com/7gT8i9O.png"></span>
                  <span class="btn-inner--text"><img src="/assets/img/loading.gif" width="20" id="loadingimg" style="display: none;"/> Discord</span>
                </a>
                <br/>
                <small><font color="gray">
                <?php
                if (isset($_SESSION["redirafterlogin"])) {
                  echo "You will be automatically redirected to <b>" . $_SESSION["redirafterlogin"] . "</b>.";
                }
                ?>
                </font></small>
                <br/>
                <small><font color="gray">By logging in, you agree to our <a href="<?= $_CONFIG["privacypolicy"] ?>">Privacy Policy</a> and our <a href="<?= $_CONFIG["termsofservice"] ?>">Terms Of Service</a>.</font></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <!-- <footer class="py-5" id="footer-main">
    <div class="container">
      <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; 2022 <a href="https://xshadow.me" class="font-weight-bold ml-1" target="_blank">X_Shadow_#5962</a> - Theme by <a href="https://creativetim.com" target="_blank">Creative Tim</a>
          </div>
        </div>
      </div>
    </div>
  </footer> -->
  <!-- Argon Scripts -->
  <!-- Core -->
  <script>
      function showLoading() {
          document.getElementById("loadingimg").style.display = "inline";
      }
  </script>
  <script src="/assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="/assets/js/argon.js?v=1.2.0"></script>
</body>

</html>
