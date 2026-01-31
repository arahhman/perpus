import AdminLayout from './layouts/AdminLayout.vue'
import SiswaLayout from './layouts/SiswaLayout.vue'
const routes = [
    {
        path: '/403',
        name: 'Forbidden',
        component: () => import('./layouts/403.vue'),
    },
    {
        path: '/app',
        redirect: () => {
        const user = window.authUser

        if (!user) {
            return '/login'
        }

        return user.role === 'admin'
            ? '/app/admin'
            : '/app/siswa'
        }
    },
    {
        path: '/app/admin',
        component: AdminLayout,
        children: [
            {
                path: '',
                name: 'admin.home',
                component: () => import('./components/pages/admin/Home.vue'),
            },
            {
                path: 'master-buku',
                name: 'admin.buku',
                component: () => import('./components/pages/admin/Buku.vue')
            },
            {
                path: 'peminjaman-buku',
                name: 'admin.peminjaman',
                component: () => import('./components/pages/admin/Peminjaman.vue')
            },
            {
                path: 'laporan-peminjaman',
                name: 'admin.laporan-peminjaman',
                component: () => import('./components/pages/admin/LaporanPeminjaman.vue')
            }
        ],
        meta: { role: 'admin', requiresAuth: true }
    },
    {
        path: '/app/siswa',
        component: SiswaLayout,
        children: [
            {
                path: '',
                name: 'siswa.home',
                component: () => import('./components/pages/siswa/Home.vue'),
            },
            {
                path: 'peminjaman-buku',
                name: 'siswa.peminjaman',
                component: () => import('./components/pages/siswa/Peminjaman.vue')
            },
            {
                path: 'laporan-peminjaman',
                name: 'siswa.laporan-peminjaman',
                component: () => import('./components/pages/siswa/LaporanPeminjaman.vue')
            }

        ],

        meta: { role: 'siswa', requiresAuth: true }
    },
]

export default routes