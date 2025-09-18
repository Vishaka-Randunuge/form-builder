<template>
  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto">
      <h1 class="text-2xl font-semibold mb-6">All Forms Submissions</h1>

      <div v-if="forms.length === 0" class="text-gray-600">No forms available.</div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="form in forms" :key="form.id" class="p-4 border rounded shadow-sm bg-white flex flex-col justify-between">
          <div>
            <p class="font-medium text-lg mb-2">{{ form.title }}</p>
            <p class="text-gray-600">{{ form.submissions_count }} submission(s)</p>
          </div>
          <Link
            :href="`/forms/${form.id}/submissions`"
            class="mt-4 px-3 py-1 bg-blue-600 text-white rounded text-center hover:bg-blue-700"
          >
            View Submissions
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  forms: Array
})
</script>
