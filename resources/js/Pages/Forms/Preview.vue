<template>
  <AuthenticatedLayout>
    <div class="max-w-3xl mx-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Preview Form: {{ form.title }}</h1>
        <Link href="/forms" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
          Back to Forms
        </Link>
      </div>

      <div v-if="form.fields.length === 0" class="text-gray-600">
        This form has no fields.
      </div>

      <form v-else @submit.prevent="submitForm">
        <div v-for="field in form.fields" :key="field.id" class="mb-4">
          <label class="block font-medium mb-1">{{ field.label }}</label>

          <!-- Text input -->
          <input
            v-if="field.type === 'text'"
            v-model="formData[field.id]"
            type="text"
            placeholder="Enter text"
            class="w-full border rounded px-3 py-2"
          />

          <!-- Textarea -->
          <textarea
            v-else-if="field.type === 'textarea'"
            v-model="formData[field.id]"
            placeholder="Enter text"
            class="w-full border rounded px-3 py-2"
            rows="4"
          ></textarea>

          <!-- Checkbox -->
<div v-else-if="field.type === 'checkbox'" class="flex flex-col gap-2">
  <label
    v-for="(option, index) in field.options"
    :key="field.id + '_chk_' + index"
    class="inline-flex items-center gap-2"
  >
    <input
      type="checkbox"
      :value="option.value"
      v-model="formData[field.id]"
      class="form-checkbox"
    />
    <span>{{ option.label }}</span>
  </label>
</div>

<!-- Radio -->
<div v-else-if="field.type === 'radio'" class="flex flex-col gap-2">
  <label
    v-for="(option, index) in field.options"
    :key="field.id + '_rad_' + index"
    class="inline-flex items-center gap-2"
  >
    <input
      type="radio"
      :name="'radio_' + field.id"
      :value="option.value"
      v-model="formData[field.id]"
      class="form-radio"
    />
    <span>{{ option.label }}</span>
  </label>
</div>


          
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
          Submit
        </button>

        <p v-if="successMessage" class="mt-4 text-green-700">{{ successMessage }}</p>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  form: { type: Object, required: true }
});

const formData = reactive({});
const successMessage = ref('');

// initialize formData
props.form.fields.forEach(field => {
  formData[field.id] = field.type === 'checkbox' ? [] : '';
});

function submitForm() {
  router.post(`/forms/${props.form.id}/submit`, { fields: formData }, {
    onSuccess: page => {
      const count = page.props?.form?.submissions_count ?? 0;
      successMessage.value = `Form submitted successfully! Total submissions: ${count}`;

      // reset
      props.form.fields.forEach(field => {
        formData[field.id] = field.type === 'checkbox' ? [] : '';
      });
    }
  });
}
</script>
