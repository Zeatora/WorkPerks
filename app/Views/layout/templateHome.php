<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/75c1df6294.js" crossorigin="anonymous"></script>
    <title><?= $title ?></title>
</head>

<body>
    <?php 
    $session = session();
    $isLoggedIn = $session->get('DataUser.login');

    $isLoggedIn = isset($isLoggedIn) ? $isLoggedIn : false; 
    
    ?>
    <div class="container-fluid px-md-5 mt-3">
        <div class="row justify-content-between">
            <div class="col-md-8 order-md-last">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <a class="navbar-brand" href="index.html">WorkPerks <span></span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="social-media">
                    <!-- <p class="mb-0 d-flex">
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
                        <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-dribbble"><i class="sr-only">Dribbble</i></span></a>
                    </p> -->
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span> Menu
            </button>

            <div class="navbar-content d-flex justify-content-between w-100">
                <div class="navbar-links">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item <?= (current_url() == base_url('home')) ? 'active' : '' ?>">
                            <a href="<?= base_url('home') ?>" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item <?= (current_url() == base_url('contact')) ? 'active' : '' ?>">
                            <a href="<?= base_url('contact') ?>" class="nav-link">Contact</a>
                        </li>
                        <li class="nav-item <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard') ?>" class="nav-link">Dashboard</a>
                        </li>
                        <li class="nav-item <?= (current_url() == base_url('dashboardF')) ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboardF') ?>" class="nav-link">Firebase Testing</a>
                        </li>
                    </ul>
                </div>
                <?php if ($isLoggedIn):  ?>
                <div class="navbar-links">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item <?= (current_url() == base_url('login')) ? 'active' : '' ?>">
                            <a href="<?= base_url('/function/logout/logoutAccount') ?>" class="nav-link">LOG OUT</a>
                        </li>
                    </ul>
                </div>
                
                <?php elseif (!$isLoggedIn): ?>
                    <div class="navbar-links">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item <?= (current_url() == base_url('login')) ? 'active' : '' ?>">
                            <a href="<?= base_url('/login') ?>" class="nav-link">LOG IN</a>
                        </li>
                        <li class="nav-item <?= (current_url() == base_url('register')) ? 'active' : '' ?>">
                            <a href="<?= base_url('/register') ?>" class="nav-link">SIGN UP</a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>




    <?php if (session('status') === 'errors'): ?>
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <ul>
                <?php foreach (session('message') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (session('status') == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <?= session('message') ?>
        </div>
    <?php endif; ?>


   
?>

    <?= $this->renderSection('content') ?>




    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


</body>

<style>
    .custom-alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        width: 80%;
        max-width: 500px;
    }

    .navbar .navbar-nav {
        width: 100%;
        /* Ensure navbar takes width of the container */
        justify-content: center;
        /* Center the links */
    }
</style>

<script>
    setTimeout(function() {
        let alertBox = document.querySelector('.alert');
        if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 5000); // 5000ms = 5 seconds
</script>

</html>