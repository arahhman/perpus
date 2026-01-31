<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Master Buku</h3>
        <button class="btn btn-success float-right" @click="showAddModal">Add Buku</button>
      </div>
      <div class="card-body">
        <table id="bukuTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Pengarang</th>
              <th>Penerbit</th>
              <th>Tahun Terbit</th>
              <th>ISBN</th>
              <th>Stok</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <!-- Modal Add/Edit -->
    <div class="modal fade" id="bukuModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modalTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveBuku">
              <div class="form-group">
                <label>Judul</label>
                <input type="text" v-model="form.judul" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Pengarang</label>
                <input type="text" v-model="form.pengarang" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Penerbit</label>
                <input type="text" v-model="form.penerbit" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" v-model="form.tahun_terbit" class="form-control" required />
              </div>
              <div class="form-group">
                <label>ISBN</label>
                <input type="text" v-model="form.isbn" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Jumlah Stok</label>
                <input type="number" v-model="form.jumlah_stok" class="form-control" min="0" />
              </div>
              <button type="submit" class="btn btn-primary">{{ form.id ? 'Update' : 'Save' }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "MasterBuku",
  data() {
    return {
      form: {
        id: null,
        judul: "",
        pengarang: "",
        penerbit: "",
        tahun_terbit: "",
        isbn: "",
        jumlah_stok: 0,
      },
      modalTitle: "Add Buku",
      baseUrl: "/admin/master-buku",
      dataTable: null,
    };
  },
  mounted() {
    this.initDataTable();
  },
  methods: {
    initDataTable() {
      if (this.dataTable) {
        this.dataTable.destroy();
        $('#bukuTable').empty();
      }

      this.dataTable = $('#bukuTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: this.baseUrl,
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'judul', name: 'judul' },
          { data: 'pengarang', name: 'pengarang' },
          { data: 'penerbit', name: 'penerbit' },
          { data: 'tahun_terbit', name: 'tahun_terbit' },
          { data: 'isbn', name: 'isbn' },
          { data: 'jumlah_stok', name: 'jumlah_stok' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        responsive: true,
        autoWidth: false,
      });

      $('#bukuTable').on('click', '.edit', (e) => {
        const id = $(e.currentTarget).data('id');
        this.editBuku(id);
      });
      $('#bukuTable').on('click', '.delete', (e) => {
        const id = $(e.currentTarget).data('id');
        this.deleteBuku(id);
      });
    },

    showAddModal() {
      this.modalTitle = "Add Buku";
      this.form = {
        id: null,
        judul: "",
        pengarang: "",
        penerbit: "",
        tahun_terbit: "",
        isbn: "",
        jumlah_stok: 0,
      };
      $("#bukuModal").modal("show");
    },

    editBuku(id) {
      axios.get(`${this.baseUrl}/${id}`).then((res) => {
        const data = res.data;
        this.modalTitle = "Edit Buku";
        this.form = {
            id: data[0].id,
            judul: data[0].judul,
            pengarang: data[0].pengarang,
            penerbit: data[0].penerbit,
            tahun_terbit: data[0].tahun_terbit,
            isbn: data[0].isbn,
            jumlah_stok: data[0].jumlah_stok ?? 0,
        };
        $("#bukuModal").modal("show");
      });
    },

    saveBuku() {
      if (this.form.id) {
        axios.put(`${this.baseUrl}/${this.form.id}`, this.form).then(() => {
          $("#bukuModal").modal("hide");
          this.dataTable.ajax.reload(null, false);
        });
      } else {
        axios.post(this.baseUrl, this.form).then(() => {
          $("#bukuModal").modal("hide");
          this.dataTable.ajax.reload(null, false);
        });
      }
    },

    deleteBuku(id) {
      if (confirm("Hapus data ini?")) {
        axios.delete(`${this.baseUrl}/${id}`).then(() => {
          this.dataTable.ajax.reload(null, false);
        });
      }
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
