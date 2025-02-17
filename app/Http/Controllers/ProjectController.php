<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ])->validate();

        $project = Project::query()->create($data);

        $project->users()->attach(auth()->id());

        return redirect()->back();
    }
}
