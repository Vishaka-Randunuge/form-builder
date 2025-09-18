<template>
  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto">
      <h1 class="text-2xl font-semibold mb-6">Saved Forms</h1>

      <div v-if="forms.length === 0" class="text-gray-600">
        No forms yet. <Link href="/forms/create" class="text-blue-600">Create one</Link>.
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="form in forms" :key="form.id" class="p-4 border rounded shadow-sm bg-white">
          <h2 class="font-medium text-lg mb-2">{{ form.title }}</h2>
          <div class="flex gap-2">
            <Link :href="`/forms/${form.id}/edit`" class="px-3 py-1 bg-blue-600 text-white rounded">Edit</Link>
            <Link :href="`/forms/${form.id}/preview`" class="px-3 py-1 border rounded">Preview</Link>
            <button @click="deleteForm(form.id)" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link, router } from '@inertiajs/vue3'

defineProps({ forms: Array })

function deleteForm(id) {
  if (confirm("Delete this form?")) {
    router.delete(`/forms/${id}`)
  }
}
</script>
