<template>
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#">
            <i class="fas fa-bars"></i>
          </a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="/logout"
             class="nav-link"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
          </a>
        </li>
      </ul>

      <form id="logout-form" action="/logout" method="POST" class="d-none">
        <input type="hidden" name="_token" :value="csrf">
      </form>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="/" class="brand-link">
        <span class="brand-text font-weight-light">Perpustakaan</span>
      </a>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column">
            <li class="nav-item">
              <router-link :to="{ name: 'admin.home' }" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'admin.buku' }" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Master Buku</p>
              </router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'admin.peminjaman' }" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Peminjaman Buku</p>
              </router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'admin.laporan-peminjaman' }" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>Laporan Peminjaman Buku</p>
              </router-link>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Content -->
    <div class="content-wrapper">
      <section class="content pt-3">
        <router-view />
      </section>
    </div>

  </div>
</template>

<script>
export default {
    mounted() {
        document.body.classList.add(
            'hold-transition',
            'sidebar-mini',
            'layout-fixed'
        )
    },
    computed: {
        csrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
}
</script>
