<template>
  <div>
    <!-- Card -->
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">Perpustakaan</h3>
      </div>

      <div class="card-body">
        <table id="laporanTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nim</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>Jenis Kelamin</th>
              <th>Tanggal Lahir</th>
              <th>Program Studi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      mahasiswas: [],
      dt: null,
    };
  },
  mounted() {
    this.initDataTable();
  },
  methods: {
    initDataTable() {
      this.dt = $('#laporanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "/admin/mahasiswa"
        },
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'nim' },
          { data: 'nama' },
          { data: 'alamat' },
          { data: 'jenis_kelamin' },
          { data: 'ttl' },
          { data: 'program_studi' }
        ],
        responsive: true,
        autoWidth: false,
      });
    },

    applyFilter() {
      this.dt.ajax.reload();
    },

    resetFilter() {
      this.filter = {
        nim: "",
        id_buku: "",
        tanggal_pinjam: "",
        tanggal_kembali: "",
        lama: "",
      };
      this.dt.ajax.reload();
    }
  }
};
</script>

<style scoped>
.table td, .table th {
  vertical-align: middle;
}
</style>
