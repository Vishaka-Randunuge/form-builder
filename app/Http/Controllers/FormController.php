<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::withCount('fields')->orderByDesc('created_at')->get();
        return Inertia::render('Forms/Index', ['forms' => $forms]);
    }

    public function create()
    {
        return Inertia::render('Forms/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|in:text,textarea,checkbox,radio',
            'fields.*.required' => 'boolean',
            'fields.*.order' => 'nullable|integer',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.*.label' => 'required_with:fields.*.options|string|max:255',
        ]);

        DB::transaction(function () use ($data) {
            $form = Form::create([
                'title' => $data['title'],
                'user_id' => auth()->id(),
            ]);

            foreach ($data['fields'] as $i => $f) {
                $field = $form->fields()->create([
                    'label' => trim($f['label']),
                    'type' => $f['type'],
                    'required' => !empty($f['required']),
                    'order' => $f['order'] ?? $i,
                ]);

                // Only create options if type is checkbox or radio
                if (in_array($f['type'], ['checkbox', 'radio'], true) && !empty($f['options'])) {
                    foreach ($f['options'] as $j => $opt) {
                        $label = trim($opt['label'] ?? '');
                        if ($label === '') continue; // skip empty labels
                        $field->options()->create([
                            'label' => $label,
                            'value' => $label, // force value = label
                            'order' => $j,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('forms.index')->with('success', 'Form created successfully.');
    }

    public function edit(Form $form)
    {
        $form->load('fields.options');
        return Inertia::render('Forms/Edit', ['form' => $form]);
    }

    public function update(Request $request, Form $form)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'fields' => 'required|array|min:0',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|in:text,textarea,checkbox,radio',
            'fields.*.required' => 'boolean',
            'fields.*.order' => 'nullable|integer',
            'fields.*.options' => 'nullable|array',
            'fields.*.options.*.label' => 'required_with:fields.*.options|string|max:255',
        ]);

        DB::transaction(function () use ($form, $data) {
            $form->update(['title' => $data['title']]);

            // Delete existing fields & options
            $form->fields()->each(function ($field) {
                $field->options()->delete();
                $field->delete();
            });

            foreach ($data['fields'] as $i => $f) {
                $field = $form->fields()->create([
                    'label' => trim($f['label']),
                    'type' => $f['type'],
                    'required' => !empty($f['required']),
                    'order' => $f['order'] ?? $i,
                ]);

                if (in_array($f['type'], ['checkbox', 'radio'], true) && !empty($f['options'])) {
                    foreach ($f['options'] as $j => $opt) {
                        $label = trim($opt['label'] ?? '');
                        if ($label === '') continue;
                        $field->options()->create([
                            'label' => $label,
                            'value' => $label,
                            'order' => $j,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('forms.index')->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form)
    {
        $form->fields()->each(function ($field) {
            $field->options()->delete();
            $field->delete();
        });

        $form->delete();

        return redirect()->route('forms.index')->with('success', 'Form deleted.');
    }

    public function preview(Form $form)
    {
        $form->load('fields.options');
        return Inertia::render('Forms/Preview', ['form' => $form]);
    }
}
