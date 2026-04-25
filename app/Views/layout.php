<?php
// Judul dinamis berdasarkan URL
$hlm = "Home";
if (uri_string() != "") {
    // Ambil segment pertama dari URL, misal /tutorial/create → Tutorial
    $segments = explode('/', uri_string());
    $hlm = ucwords($segments[0]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Neatly - <?= $hlm ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url() ?>neatly_logo.png" rel="icon">
    <link href="<?= base_url() ?>neatly_logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?= base_url() ?>NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url() ?>NiceAdmin/assets/css/style.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url() ?>css/custom.css" rel="stylesheet">
</head>

<body>

    <?= $this->include('components/header') ?>

    <?= $this->include('components/sidebar') ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1><?= $hlm ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
                    <?php if ($hlm != "Home") { ?>
                        <li class="breadcrumb-item active"><?= $hlm ?></li>
                    <?php } ?>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <?= $this->renderSection('content') ?>
        </section>

    </main><!-- End #main -->

    <?= $this->include('components/footer') ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/quill/quill.min.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?= base_url() ?>NiceAdmin/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="<?= base_url() ?>NiceAdmin/assets/js/main.js"></script>

    <!-- Custom Sidebar JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.querySelector('.custom-toggle-arrow');
            const body = document.querySelector('body');
            
            if (toggleBtn) {
                // Check local storage for sidebar state to persist layout
                const savedState = localStorage.getItem('sidebar_state');
                if (savedState === 'expanded') {
                    body.classList.add('toggle-sidebar');
                    toggleBtn.innerHTML = '&laquo;';
                } else if (savedState === 'slim') {
                    body.classList.remove('toggle-sidebar');
                    toggleBtn.innerHTML = '&raquo;';
                }

                toggleBtn.addEventListener('click', (e) => {
                    // Prevent navigation click bubbling if it was placed inside an anchor tag
                    e.preventDefault();
                    e.stopPropagation();

                    // Wait slightly for NiceAdmin's script to toggle the class on body
                    setTimeout(() => {
                        if (body.classList.contains('toggle-sidebar')) {
                            // Sidebar is OPEN/LOCKED
                            toggleBtn.innerHTML = '&laquo;'; // <<
                            localStorage.setItem('sidebar_state', 'expanded');
                        } else {
                            // Sidebar is SLIM
                            toggleBtn.innerHTML = '&raquo;'; // >>
                            localStorage.setItem('sidebar_state', 'slim');
                        }
                    }, 50);
                });
            }

            // Optional hover visual cue for arrow change (only if not already locked open)
            const sidebar = document.getElementById('sidebar');
            if(sidebar && toggleBtn) {
                sidebar.addEventListener('mouseenter', () => {
                    if(!body.classList.contains('toggle-sidebar') && window.innerWidth >= 1200) {
                        // Showing expanded mode on hover, doesn't lock it, but the arrow should suggest "click to lock"
                        // Or we can leave it as >> meaning "click to expand fully/lock". 
                        // It's probably better to keep it >> when hovering until locked, then change to <<.
                    }
                });
            }
        });
    </script>

    <?= $this->renderSection('page-script') ?>

</body>

</html>