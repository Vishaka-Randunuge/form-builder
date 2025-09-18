<template>
  <div class="w-1/6 bg-gray-200 text-black fixed inset-y-0 left-0 p-4 space-y-6 py-6 px-4">
    <!-- Title and Subtitle -->
    <div class="px-4 py-6">
      <h1 class="text-xl font-bold">Form Builder</h1>
      <p class="text-sm text-gray-600">Manage your forms</p>
    </div>

    <!-- Navigation Links -->
    <nav class="space-y-2 py-6 px-4">
        <Link href="/dashboard" class="block p-2 rounded hover:bg-gray-300">Dashboard</Link>
        <Link href="/forms" class="block p-2 rounded hover:bg-gray-300">Forms</Link>
        <Link href="/forms/create" class="block p-2 rounded hover:bg-gray-300">Form Builder</Link>
        <Link href="/submissions" class="block p-2 rounded hover:bg-gray-300">Submissions</Link>
    </nav>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<style scoped>
/* Scoped CSS */
</style>
