<template>
  <AuthenticatedLayout>
    <div class="max-w-6xl mx-auto p-4">

      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Submissions: {{ form.title }}</h1>
        <Link href="/forms" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
          Back to Forms
        </Link>
      </div>

      <!-- No submissions message -->
      <div v-if="submissions.length === 0" class="text-gray-600">
        No submissions yet.
      </div>

      <!-- Submissions grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="submission in submissions" :key="submission.id" class="p-4 border rounded shadow-sm bg-white flex flex-col justify-between">
          
          <!-- Submission info -->
          <div>
            <p class="font-medium mb-1">Submission #{{ submission.id }}</p>
            <p class="text-gray-600 text-sm mb-2">
              Submitted at: {{ new Date(submission.created_at).toLocaleString() }}
            </p>

            <!-- Answers preview -->
            <div v-for="(answer, index) in submission.answers.slice(0, 3)" :key="answer.id" class="mb-1">
              <span class="font-medium text-gray-700">{{ answer.field.label }}:</span>
              <span class="text-gray-800">
                {{ formatAnswer(answer) }}
              </span>
            </div>
            
            <!-- Show if more fields exist -->
            <p v-if="submission.answers.length > 3" class="text-sm text-gray-500">
              +{{ submission.answers.length - 3 }} more fields
            </p>
          </div>

          <!-- View Details button -->
          <Link
            :href="`/forms/${form.id}/submissions/${submission.id}`"
            class="mt-4 px-3 py-1 bg-blue-600 text-white rounded text-center hover:bg-blue-700"
          >
            View Details
          </Link>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  form: Object,
  submissions: Array
})

// Format answer for preview
const formatAnswer = (answer) => {
  if (!answer.value) return ''
  
  if (answer.field.type === 'checkbox' || answer.field.type === 'radio') {
    try {
      const parsed = JSON.parse(answer.value)
      if (Array.isArray(parsed)) return parsed.join(', ')
      return parsed
    } catch {
      return answer.value // fallback if not JSON
    }
  }

  return answer.value
}
</script>
