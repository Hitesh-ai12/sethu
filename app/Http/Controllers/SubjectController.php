<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Subject;

    class SubjectController extends Controller
    {
        // Fetch all subjects

        public function getSubjects()
        {
            $subjects = Subject::all();
            return response()->json(['subjects' => $subjects], 200);
        }

        // Fetch subjects for API

        public function fetchSubjects()
        {
            $subjects = Subject::all(['id', 'name']);
            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ], 200);
        }

        // Add a new subject

        public function addSubject(Request $request)
        {
            $request->validate([
                'name' => 'required|string|unique:subjects|max:255',
            ]);

            $subject = Subject::create(['name' => $request->name]);

            return response()->json(['success' => true, 'subject' => $subject], 201);
        }

        // Update a subject

        public function updateSubject(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|unique:subjects,name,' . $id . '|max:255',
            ]);

            $subject = Subject::find($id);
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
            }

            $subject->update(['name' => $request->name]);

            return response()->json(['success' => true, 'subject' => $subject], 200);
        }

        // Delete a subject

        public function deleteSubject($id)
        {
            $subject = Subject::find($id);
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
            }

            $subject->delete();

            return response()->json(['success' => true], 200);
        }

    }
