<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
      <h1 class="text-6xl font-bold text-red-600">403</h1>
      <p class="text-xl mt-4 text-gray-700">
        Kamu Tidak Punya Akses Ke Halaman Ini
      </p>

      <button
        @click="goHome"
        class="mt-6 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Kembali ke Home
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

function goHome() {
  router.push('/app')
}
</script>
