<template>
  <div class="w-1/6 bg-gray-200 text-black fixed inset-y-0 left-0 p-4 space-y-6 py-6 px-4">
    <!-- Title and Subtitle -->
    <div class="px-4 py-6">
      <h1 class="text-xl font-bold">Form Builder</h1>
      <p class="text-sm text-gray-600">Manage your forms</p>
    </div>

    <!-- Navigation Links -->
    <nav class="space-y-2 py-6 px-4">
      <inertia-link href="/dashboard" class="block p-2 rounded hover:bg-gray-300">Dashboard</inertia-link>
      <inertia-link href="/forms" class="block p-2 rounded hover:bg-gray-300">Forms</inertia-link>
      <inertia-link href="/forms/create" class="block p-2 rounded hover:bg-gray-300">Form Builder</inertia-link>
      <inertia-link href="/submissions" class="block p-2 rounded hover:bg-gray-300">Submissions</inertia-link>
    </nav>
  </div>
</template>

<script setup>
// Any logic related
</script>

<style scoped>
/* Scoped CSS */
</style>
