<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ResourceLibraryController extends Controller
{

    public function getUniqueSubjects()
    {
        $subjects = User::whereNotNull('subject')->distinct()->pluck('subject');
        return response()->json(['unique_subjects' => $subjects]);
    }

}
