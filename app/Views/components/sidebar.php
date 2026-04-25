<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <div class="d-flex align-items-center" style="position: relative;">
                <a class="nav-link w-100 <?= uri_string() == '' ? '' : 'collapsed' ?>" href="<?= base_url('/') ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
                <!-- Custom Arrow Toggle inside sidebar -->
                <span class="custom-toggle-arrow toggle-sidebar-btn">&raquo;</span>
            </div>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link <?= str_contains(uri_string(), 'tutorial') ? '' : 'collapsed' ?>" href="<?= base_url('tutorial') ?>">
                <i class="bi bi-journal-text"></i>
                <span>Tutorial</span>
            </a>
        </li><!-- End Tutorial Nav -->

        <li class="nav-heading">Account</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li><!-- End Logout Nav -->

    </ul>

</aside><!-- End Sidebar-->
