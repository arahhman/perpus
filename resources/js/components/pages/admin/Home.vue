<template>
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h4>Jumlah Buku</h4>
                    <p>{{ dashboard.totalbuku }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h4>Jumlah Peminjaman Aktif</h4>
                    <p>{{ dashboard.peminjaman }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h4>Jumlah Siswa</h4>
                    <p>{{ dashboard.totalsiswa }}</p>
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
                    totalsiswa: 0
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
