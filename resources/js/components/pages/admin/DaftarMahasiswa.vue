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
              <th>Status</th>
              <th>Action</th>
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
            { data: 'program_studi' },
            { data: 'flag_aktif' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            responsive: true,
            autoWidth: false,
        });

        $('#laporanTable').on('click', '.toggleAktifBtn', function() {
            let id = $(this).data('id');
            if(confirm('Apakah yakin ingin mengubah status aktif mahasiswa ini?')) {
                $.ajax({
                    url: '/admin/mahasiswa/' + id,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        toggle_aktif: true
                    },
                    success: function(response){
                        alert('Status berhasil diubah menjadi: ' + response.flag_aktif);
                        $('#laporanTable').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('#laporanTable').on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            if(confirm('Apakah yakin ingin menghapus mahasiswa ini?')) {
                $.ajax({
                    url: '/admin/mahasiswa/' + id,
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    success: function(response){
                        alert(response.message);
                        $('#laporanTable').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menghapus');
                    }
                });
            }
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
