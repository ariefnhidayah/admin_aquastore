  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php $user = $this->session->userdata('user'); ?>

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ADMIN</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?= $menu == 'dashboard' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('') ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <li class="nav-item <?= $menu == 'kategori' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('categories') ?>">
          <i class="fas fa-bars"></i>
          <span>Kategori</span></a>
      </li>

      <div class="nav-item <?= $menu == 'report' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('reports') ?>">
          <i class="fas fa-file-invoice"></i>
          <span>Laporan</span></a>
      </div>

      <div class="nav-item <?php echo $menu == 'seller' ? 'active' : '' ?>">
        <a href="<?php echo base_url('seller') ?>" class="nav-link">
          <i class="fa fa-user"></i> <span>Penjual</span>
        </a>
      </div>

      <div class="nav-item <?= $menu == 'balance_seller' ? 'active' : "" ?>">
        <a href="<?= base_url('balance_seller') ?>" class="nav-link">
          <i class="fas fa-money-bill-wave"></i>
          <span>Saldo Seller</span>
        </a>
      </div>

      <div class="nav-item <?= $menu == 'balance_user' ? 'active' : "" ?>">
        <a href="<?= base_url('balance_user') ?>" class="nav-link">
          <i class="fas fa-money-bill-wave"></i>
          <span>Saldo User</span>
        </a>
      </div>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->