<template>
  <div>
    <!-- Card -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Perpustakaan</h3>
        <button class="btn btn-primary btn-sm float-right" @click="showAddModal = true">Add Mahasiswa</button>
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

        <div class="modal" tabindex="-1" :class="{ 'd-block': showAddModal }" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Mahasiswa</h5>
                        <button type="button" class="close"  @click="showAddModal = false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitAddMahasiswa">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input v-model="newMahasiswa.email" type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input v-model="newMahasiswa.nim" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input v-model="newMahasiswa.nama" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea v-model="newMahasiswa.alamat" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label><br>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="L" v-model="newMahasiswa.jenis_kelamin" required>
                                <label class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="P" v-model="newMahasiswa.jenis_kelamin" required>
                                <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ttl" class="form-label">Tanggal Lahir</label>
                                <input v-model="newMahasiswa.ttl" type="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="program_studi" class="form-label">Program Studi</label>
                                <input v-model="newMahasiswa.program_studi" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input v-model="newMahasiswa.password" type="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input v-model="newMahasiswa.password_confirmation" type="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" @click="showAddModal = false">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
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
        showAddModal: false,
        newMahasiswa: {
            email: '',
            nim: '',
            nama: '',
            alamat: '',
            jenis_kelamin: '',
            ttl: '',
            program_studi: '',
            password: '',
            password_confirmation: ''
        }
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
    },

    submitAddMahasiswa() {
        axios.post('api/admin/mahasiswa', this.newMahasiswa)
        .then((res) => {
            alert(res.data.message || 'Mahasiswa berhasil ditambahkan');
            this.dt.ajax.reload();
            this.showAddModal = false;
            this.resetNewMahasiswa();
        })
        .catch((err) => {
            if (err.response) {
                if (err.response.data.errors) {
                    let messages = [];
                    for (const field in err.response.data.errors) {
                    messages.push(err.response.data.errors[field].join(', '));
                    }
                    alert('Terjadi kesalahan:\n' + messages.join('\n'));
                } else if (err.response.data.message) {
                    alert('Terjadi kesalahan: ' + err.response.data.message);
                } else {
                    alert('Terjadi kesalahan: Cek input');
                }
            } else {
                alert('Terjadi kesalahan: Server tidak merespon');
            }
        });

    },

    resetNewMahasiswa() {
        this.newMahasiswa = {
            email: '',
            nim: '',
            nama: '',
            alamat: '',
            jenis_kelamin: '',
            ttl: '',
            program_studi: '',
            password: '',
            password_confirmation: ''
        };
    }
  }
};
</script>

<style scoped>
.table td, .table th {
  vertical-align: middle;
}
</style>
