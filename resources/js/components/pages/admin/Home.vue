<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h5>Jumlah Buku</h5>
                        <p>{{ dashboard.totalbuku }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h5>Jumlah Mahasiswa</h5>
                        <p>{{ dashboard.totalsiswa }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h5>Jumlah Peminjaman Aktif</h5>
                        <p>{{ dashboard.peminjaman }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h5>Jumlah Peminjaman Overdue</h5>
                        <p>{{ dashboard.overdue }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'AdminHome',
        data() {
            return {
                dashboard: {
                    totalbuku: 0,
                    peminjaman: 0,
                    totalsiswa: 0,
                    overdue: 0
                },
            };
        },
        mounted() {
            this.fetchUsers();
        },
        methods: {
            async fetchUsers() {
                try {
                    const response = await axios.get('/admin/get-dashboard');
                    this.dashboard = response.data;
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        }
    }
</script>
