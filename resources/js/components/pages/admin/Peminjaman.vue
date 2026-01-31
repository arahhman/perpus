<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tambah Peminjaman</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="savePeminjaman">
          <div class="form-group">
            <label>Mahasiswa</label>
            <select v-model="form.id_user" class="form-control" @change="getBuku" required>
              <option value="" disabled>Pilih Mahasiswa</option>
              <option v-for="mhs in mahasiswas" :key="mhs.id_user" :value="mhs.id_user">
                {{ mhs.nim }} - {{ mhs.nama }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Buku</label>
            <select v-model="form.id_buku" class="form-control" @change="getStokBuku" required>
              <option value="" disabled>Pilih Buku</option>
              <option v-for="buku in bukus" :key="buku.id" :value="buku.id">
                {{ buku.judul }} (Stok: {{ buku.jumlah_stok ?? 0 }})
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Tanggal Pinjam</label>
            <input type="text" id="tanggal_pinjam" class="form-control" required />
          </div>

          <div class="form-group">
            <label>Tanggal Kembali</label>
            <input type="text" id="tanggal_kembali" class="form-control" required />
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AddPeminjaman",
  data() {
    return {
      form: {
        id_user: "",
        id_buku: "",
        tanggal_pinjam: "",
        tanggal_kembali: "",
      },
      mahasiswas: [],
      bukus: [],
    };
  },
  mounted() {
    const today = new Date();
    this.form.tanggal_pinjam = today.toISOString().substr(0,10);
    flatpickr("#tanggal_pinjam", {
        dateFormat: "Y-m-d",
        defaultDate: today,
        minDate: today,
        maxDate: today,
        onChange: (selectedDates, dateStr) => {
            this.form.tanggal_pinjam = dateStr;

            const returnDate = new Date(selectedDates[0]);
            returnDate.setDate(returnDate.getDate() + 14);

            this.fpKembali.setDate(returnDate);
            this.fpKembali.set("minDate", selectedDates[0]);
            this.fpKembali.set("maxDate", returnDate);
        }
    });

    const defaultReturn = new Date(today);
    defaultReturn.setDate(defaultReturn.getDate() + 14);
    this.form.tanggal_kembali = defaultReturn.toISOString().substr(0,10);

    this.fpKembali = flatpickr("#tanggal_kembali", {
        dateFormat: "Y-m-d",
        defaultDate: defaultReturn,
        minDate: today,
        maxDate: defaultReturn,
        onChange: (selectedDates, dateStr) => {
            this.form.tanggal_kembali = dateStr;
        }
    });
    this.getMahasiswa();
  },
  methods: {
    getMahasiswa() {
      axios.get("/list-master/mahasiswa").then((res) => {
        this.mahasiswas = res.data;
      });
    },
    getBuku() {
        axios.get("/list-master/buku", {
            params: {
                id_user: this.form.id_user
            }
        }).then((res) => {
            this.bukus = res.data;
        });
    },
    getStokBuku() {
      const buku = this.bukus.find(b => b.id === this.form.id_buku);
      if (buku.jumlah_stok < 1) {
        alert(`Stok buku saat ini: ${buku.jumlah_stok}`);
      }
    },
    savePeminjaman() {
      axios.post("/admin/peminjaman", this.form)
        .then(() => {
            alert("Peminjaman berhasil disimpan!")
            window.location.reload();
        })
        .catch(err => {
          console.error(err);
          alert("Gagal menyimpan peminjaman.");
        });
    },
  },
};
</script>

<style scoped>
.table td,
.table th {
  vertical-align: middle;
}
</style>
