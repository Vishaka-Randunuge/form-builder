<?php

namespace App\Http\Controllers;
use App\Models\Submission;
use App\Models\Form;
use App\Models\Answer;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    //

    public function index(Form $form) {
        $submissions = $form->submissions()
            ->latest()
            ->with('answers.field') // eager load answers and the field info
            ->get();
    
        return Inertia::render('Forms/Submissions', [
            'form' => $form,
            'submissions' => $submissions,
        ]);
    }
    

    public function show(Form $form, Submission $submission) {
        $submission->load('answers.field');
        return Inertia::render('Forms/SubmissionDetail', [
            'form' => $form,
            'submission' => $submission
        ]);
    }
    
    public function allFormsSubmissions() {
        // Get all forms with submission count
        $forms = Form::withCount('submissions')->get();
    
        return Inertia::render('Forms/AllSubmissions', [
            'forms' => $forms
        ]);
    }

    
    public function submit(Request $request, Form $form) {
        $submission = Submission::create(['form_id' => $form->id]);
    
        foreach($form->fields as $field) {
            $value = $request->input('fields.'.$field->id); // expect array: field_id => value
            if(is_array($value)) $value = implode(',', $value);
            Answer::create([
                'submission_id' => $submission->id,
                'field_id' => $field->id,
                'value' => $value ?? '',
            ]);
        }
    
        return redirect()->route('forms.preview', $form->id)
                         ->with('success', 'Form submitted successfully!');
    }
    
}
