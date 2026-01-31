<template>
  <div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <router-link :to="{ name: 'siswa.home' }" class="navbar-brand">
          Perpustakaan
        </router-link>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link :to="{ name: 'siswa.home' }" class="nav-link">
                Home
              </router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'siswa.peminjaman' }" class="nav-link">
                Peminjaman
              </router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'siswa.laporan-peminjaman' }" class="nav-link">
                Laporan Peminjaman
              </router-link>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link" @click.prevent="logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Content -->
    <main class="container mt-4">
      <router-view />
    </main>

  </div>
</template>

<script>
export default {
  methods: {
    logout() {
        axios.post("/logout")
        .then(() => {
          window.location.href = "/login";
        })
        .catch((err) => {
          console.error("Logout gagal", err);
          alert("Gagal logout!");
        });
    }
  }
}
</script>
