<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto">
      <h1 class="text-2xl font-semibold mb-4">Create Form</h1>

      <!-- Form title -->
      <div class="bg-white p-4 shadow rounded">
        <label class="block mb-2 font-medium">Form Title</label>
        <input v-model="form.title" class="w-full border rounded p-2" placeholder="Enter form title"/>
      </div>

      <!-- Fields -->
      <div class="mt-6 space-y-4">
        <div v-for="(field, idx) in form.fields" :key="field.localId" class="bg-white p-4 border rounded">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <input v-model="field.label" class="w-full border rounded p-2" placeholder="Field label"/>
              <div class="mt-2 flex items-center gap-3">
                <select v-model="field.type" class="border rounded p-1" @change="onTypeChange(field)">
                  <option value="text">Text input</option>
                  <option value="textarea">Text area</option>
                  <option value="checkbox">Checkbox (multi)</option>
                  <option value="radio">Radio (single)</option>
                </select>

                <label class="flex items-center gap-2 text-sm">
                  <input type="checkbox" v-model="field.required" />
                  Required
                </label>
              </div>
            </div>

            <!-- Actions -->
            <div class="ml-4 flex flex-col gap-2">
              <button @click="moveUp(idx)" :disabled="idx===0" class="px-2 py-1 border rounded">↑</button>
              <button @click="moveDown(idx)" :disabled="idx===form.fields.length-1" class="px-2 py-1 border rounded">↓</button>
              <button @click="removeField(idx)" class="px-2 py-1 border rounded text-red-600">Delete</button>
            </div>
          </div>

          <!-- Options for checkbox/radio -->
          <div v-if="field.type==='checkbox' || field.type==='radio'" class="mt-3">
            <label class="block text-sm font-medium mb-2">Options</label>
            <div v-for="(opt, oi) in field.options" :key="oi" class="flex gap-2 items-center mb-2">
              <input v-model="opt.label" class="flex-1 border rounded p-2" placeholder="Option label" />
              <button @click="removeOption(field, oi)" class="px-2 py-1 border rounded text-red-600">Remove</button>
            </div>
            <button @click="addOption(field)" class="mt-1 px-3 py-1 bg-gray-100 border rounded"
              :disabled="field.options.some(o => !o.label.trim())"
              title="Cannot add new option until all existing options have labels">+ Add option</button>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-6 flex items-center gap-3">
        <div class="flex gap-2">
          <button @click="addField('text')" class="px-3 py-2 border rounded">+ Text</button>
          <button @click="addField('textarea')" class="px-3 py-2 border rounded">+ Textarea</button>
          <button @click="addField('checkbox')" class="px-3 py-2 border rounded">+ Checkbox</button>
          <button @click="addField('radio')" class="px-3 py-2 border rounded">+ Radio</button>
        </div>

        <div class="ml-auto">
          <button @click="saveForm" class="px-4 py-2 bg-blue-600 text-white rounded">Save form</button>
          <button @click="togglePreview" class="ml-2 px-4 py-2 border rounded">Preview</button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({ title: '', fields: [] });
const showPreview = ref(false);

// --- Field helpers ---
function addField(type='text') {
  const newField = { localId: Date.now()+Math.random(), label:'', type, required:false, options:[] };
  if(type==='checkbox'||type==='radio') newField.options.push({label:''});
  form.fields.push(newField);
}
function removeField(idx) { form.fields.splice(idx,1); }
function moveUp(idx) { if(idx===0)return; form.fields.splice(idx-1,0,form.fields.splice(idx,1)[0]); }
function moveDown(idx) { if(idx===form.fields.length-1)return; form.fields.splice(idx+1,0,form.fields.splice(idx,1)[0]); }
function onTypeChange(field) { 
  if(['checkbox','radio'].includes(field.type) && field.options.length===0) field.options.push({label:''}); 
  if(!['checkbox','radio'].includes(field.type)) field.options=[]; 
}
function addOption(field) { if(field.options.length && !field.options[field.options.length-1].label.trim()) return; field.options.push({label:''}); }
function removeOption(field, idx) { field.options.splice(idx,1); }

// --- Save form ---
function saveForm() {
  if(!form.title.trim()){ alert('Please enter a form title'); return; }
  for(const f of form.fields){
    if(!f.label.trim()){ alert('All fields must have a label'); return; }
    if(['checkbox','radio'].includes(f.type)){
      f.options=f.options.map(o=>({label:o.label.trim()})).filter(o=>o.label!=='');
      if(f.options.length===0){ alert(`Please add at least one option for "${f.label}"`); return; }
    }
  }
  form.post('/forms');
}
</script>
