<template>
  <div>
    <!-- Card -->
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">Perpustakaan</h3>
        <button class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#filterModal">
          Filter
        </button>
      </div>

      <div class="card-body">
        <table id="laporanTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Mahasiswa</th>
              <th>Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
              <th>Lama Pinjam (hari)</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Filter Peminjaman</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
            <!-- NIM Mahasiswa -->
            <div class="form-group">
              <label>Mahasiswa (NIM)</label>
              <select v-model="filter.nim" class="form-control">
                <option value="">--</option>
                <option v-for="m in mahasiswas" :key="m.nim" :value="m.nim">
                  {{ m.nama }} ({{ m.nim }})
                </option>
              </select>
            </div>

            <!-- Buku -->
            <div class="form-group">
              <label>Buku (Kode Buku)</label>
              <select v-model="filter.id_buku" class="form-control">
                <option value="">--</option>
                <option v-for="b in bukus" :key="b.id" :value="b.id">
                  {{ b.judul }} ({{ b.id }})
                </option>
              </select>
            </div>

            <!-- Tanggal Pinjam -->
            <div class="form-group">
              <label>Tanggal Pinjam</label>
              <input type="text" id="tanggal_pinjam" class="form-control" placeholder="YYYY-MM-DD" />
            </div>

            <!-- Tanggal Kembali -->
            <div class="form-group">
              <label>Tanggal Kembali</label>
              <input type="text" id="tanggal_kembali" class="form-control" placeholder="YYYY-MM-DD" />
            </div>

            <!-- Lama Pinjam -->
            <div class="form-group">
              <label>Lama Pinjam (hari)</label>
              <input type="number" v-model="filter.lama" class="form-control" min="1" placeholder="Contoh: 14" />
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" @click="resetFilter">Reset</button>
            <button class="btn btn-primary" @click="applyFilter" data-dismiss="modal">Apply Filter</button>
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
      bukus: [],
      mahasiswas: [],
      filter: {
        nim: "",
        id_buku: "",
        tanggal_pinjam: "",
        tanggal_kembali: "",
        lama: "",
      },
      dt: null,
    };
  },
  mounted() {
    this.loadMahasiswa();
    this.loadBuku();
    this.initFlatpickr();
    this.initDataTable();
  },
  methods: {
    loadBuku() {
      axios.get("/list-master/bukuAll").then(res => {
        this.bukus = res.data;
      });
    },

    loadMahasiswa() {
      axios.get("/siswa/mahasiswa").then((res) => {
        this.mahasiswas = res.data ? [res.data] : [];
        if (this.mahasiswas.length > 0) {
            this.filter.nim = this.mahasiswas[0].nim;
        }
      });
    },

    initFlatpickr() {
      flatpickr("#tanggal_pinjam", {
        dateFormat: "Y-m-d",
        onChange: (selectedDates, dateStr) => {
          this.filter.tanggal_pinjam = dateStr;
        }
      });

      flatpickr("#tanggal_kembali", {
        dateFormat: "Y-m-d",
        onChange: (selectedDates, dateStr) => {
          this.filter.tanggal_kembali = dateStr;
        }
      });
    },

    initDataTable() {
      this.dt = $('#laporanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "/siswa/laporan-peminjaman",
          data: (d) => {
            d.nim = this.filter.nim;
            d.id_buku = this.filter.id_buku;
            d.tanggal_pinjam = this.filter.tanggal_pinjam;
            d.tanggal_kembali = this.filter.tanggal_kembali;
            d.lama = this.filter.lama;
          }
        },
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'mahasiswa' },
          { data: 'buku' },
          { data: 'tanggal_pinjam' },
          { data: 'tanggal_kembali' },
          { data: 'statusbuku' },
          { data: 'lamappinjam' },
          {
            data: null,
            orderable: false,
            searchable: false,
            render: (data, type, row) => {
              if (row.statusbuku === 'Dipinjam') {
                return `<button class="btn btn-success btn-sm kembalikan" data-id_buku="${row.id_buku}">Kembalikan</button>`;
              }
              return '';
            }
          }
        ],
        responsive: true,
        autoWidth: false,
        drawCallback: () => {
          $('.kembalikan').off('click').on('click', (e) => {
            const buku = $(e.currentTarget).data('id_buku');
            this.kembalikanBuku(buku);
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

    kembalikanBuku(id_buku) {
        console.log(id_buku);
      if (!confirm("Apakah yakin ingin mengembalikan buku ini?")) return;

      axios.post("/siswa/kembalikan-buku", { id_buku })
        .then(() => {
          alert("Buku berhasil dikembalikan!");
          this.dt.ajax.reload();
        })
        .catch(err => {
          alert(err.response?.data?.error || 'Terjadi kesalahan!');
        });
    }
  }
};
</script>

<style scoped>
.table td, .table th {
  vertical-align: middle;
}
</style>
