<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h4>Jumlah Peminjaman Aktif</h4>
                        <p>{{ dashboard.peminjaman ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h4>Jumlah Peminjaman Overdue</h4>
                        <p>{{ dashboard.overdue ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SiswaHome',
    data() {
        return {
            dashboard: {
                peminjaman: 0
            },
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                const response = await axios.get('/siswa/get-dashboard');
                this.dashboard = response.data;
            } catch (error) {
                console.error('Error fetching siswa:', error);
            }
        }
    }
}
</script>
