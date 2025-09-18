<template>
  <AuthenticatedLayout>
    <div class="max-w-3xl mx-auto p-4 bg-white rounded shadow">
      <h1 class="text-2xl font-semibold mb-4">
        Submission #{{ submission.id }} - {{ form.title }}
      </h1>
      <p class="text-gray-600 mb-4">
        Submitted at: {{ new Date(submission.created_at).toLocaleString() }}
      </p>

      <div v-for="answer in submission.answers" :key="answer.id" class="mb-4">
        <label class="block font-medium mb-1">{{ answer.field.label }}</label>

        <!-- Text input -->
        <input
          v-if="answer.field.type === 'text'"
          type="text"
          :value="answer.value"
          readonly
          class="border rounded px-2 py-1 w-full"
        />

        <!-- Textarea -->
        <textarea
          v-else-if="answer.field.type === 'textarea'"
          :value="answer.value"
          readonly
          class="border rounded px-2 py-1 w-full"
        ></textarea>

        <!-- Checkbox / Radio -->
        <div
          v-else-if="answer.field.type === 'checkbox' || answer.field.type === 'radio'"
          class="flex flex-wrap gap-2"
        >
          <span
            v-for="option in parseAnswer(answer.value)"
            :key="option"
            class="px-2 py-1 bg-gray-200 rounded text-sm"
          >
            {{ option }}
          </span>
        </div>
      </div>

      <Link
        :href="`/forms/${form.id}/submissions`"
        class="mt-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 inline-block"
      >
        Back
      </Link>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  form: Object,
  submission: Object
})

// Safely parse checkbox/radio values
const parseAnswer = (val) => {
  if (!val) return []
  try {
    const parsed = JSON.parse(val)
    return Array.isArray(parsed) ? parsed : [parsed]
  } catch {
    // Fallback: comma-separated string
    return val.split(',').map(v => v.trim())
  }
}
</script>
