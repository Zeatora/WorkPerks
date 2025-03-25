<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/styleOther.css">
    <script src="https://kit.fontawesome.com/75c1df6294.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    $session = session();
    $isLoggedIn = $session->get('DataUser.login') ?? false;

    ?>

    <div class="container-fluid px-md-5 mt-3">
        <div class="row justify-content-between">
            <div class="col-md-8 order-md-last">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <a class="navbar-brand" href="index.html">WorkPerks <span>Manajemen Benefit Karyawan</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="social-media">
                    <!-- Social Media Links -->
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
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>">
                                <a href="<?= base_url('dashboard') ?>" class="nav-link">Dashboard</a>
                            </li>
                            <li class="nav-item <?= (current_url() == base_url('dashboardF')) ? 'active' : '' ?>">
                                <a href="<?= base_url('dashboardF') ?>" class="nav-link">Firebase Testing</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if ($isLoggedIn): ?>
                    <div class="navbar-links">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fa fa-user-circle"></span> <?= $session->get('DataUser.Data.username') ?? 'User' ?>
                                </a>
                                <ul class="dropdown-menu dropdown-custom" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?= base_url('/profile') ?>"><span class="fa fa-user"></span> Profile</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/function/logout/logoutAccount') ?>"><span class="fa fa-sign-out-alt"></span> Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php elseif (!$isLoggedIn): ?>
                    <div class="navbar-links">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item <?= (current_url() == base_url('login')) ? 'active' : '' ?>">
                                <a href="<?= base_url('/login') ?>" class="nav-link"><span class="fa fa-sign-in-alt"></span> Log In</a>
                            </li>
                            <li class="nav-item <?= (current_url() == base_url('register')) ? 'active' : '' ?>">
                                <a href="<?= base_url('/register') ?>" class="nav-link"><span class="fa fa-user-plus"></span> Sign Up</a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if (session('status') === 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <ul>
                <?php $message = session('message');
                if (is_array($message)) :?>
                <?php foreach ($message as $error): ?>
                        <li><?= esc($error) ?></li>
                <?php endforeach; ?>
                <?php elseif (is_string($message)) : ?>
                    <li><?= esc($message) ?></li>
                <?php endif; ?>

            </ul>
        </div>
    <?php elseif (session('status') === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <?= session('message') ?>
        </div>
    <?php elseif (session('status') === 'warning'): ?>
        <div class="alert alert-warning alert-dismissible fade show custom-alert" role="alert">
            <?= session('message') ?>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

    <!-- Include Required Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

    <script>
        setTimeout(function() {
            let alertBox = document.querySelector('.alert');
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 300);
            }
        }, 5000); // 5 seconds
    </script>

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
            justify-content: center;
        }

        .dropdown-custom {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .dropdown-custom .dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            transition: background-color 0.3s ease;
        }

        .dropdown-custom .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-custom .fa {
            margin-right: 8px;
        }
    </style>
</body>

</html>