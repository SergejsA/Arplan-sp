<?php

namespace App\Http\Controllers;

use App\Models\DefaultJob;
use App\Models\ParastiJob;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjektiController extends Controller
{
    public function getAll(){
        $projects = Project::where('id', '>', 0)->with('user')->get();
        $defaultJobs = DefaultJob::all();
        $parastiJobs = ParastiJob::all();
        return response()->json(['projekti' => $projects, 'default' => $defaultJobs, 'parasti' => $parastiJobs], 200);
    }

    public function create(Request $request){
        $project = new Project();
        $project->nosaukums = $request->nosaukums;
        $project->darbi = join(";", explode("\n", $request->darbi));
        $project->pievienotaja_id = Auth::user()->id;

        $project->save();

        $projects = Project::where('id', '>', 0)->with('user')->get();
        return response()->json(['projekti' => $projects], 200);
    }

    public function delete(Request $request){
        $project = Project::find($request->id);
        $project->delete();

        $projects = Project::where('id', '>', 0)->with('user')->get();
        return response()->json(['projekti' => $projects], 200);
    }

    public function update(Request $request){
        $project = Project::find($request->id);
        $project->nosaukums = $request->nosaukums;
        $project->darbi = join(";", explode("\n", $request->darbi));
        $project->stat = $request->status;

        $project->save();

        $projects = Project::where('id', '>', 0)->with('user')->get();
        return response()->json(['projekti' => $projects], 200);
    }

    public function getActive(){
        $p = Project::where('id', '>', 0)->where('stat', 'active')->get();
        $parasti = ParastiJob::all();
        return response()->json(['projekti' => $p, 'parasti' => $parasti]);
    }

    public function newDefault(Request $request){
        $job = new DefaultJob();
        $job->name = $request->name;
        $job->save();

        $jobs = DefaultJob::all();
        return response()->json(['default' => $jobs], 200);
    }

    public function newParasti(Request $request){
        $job = new ParastiJob();
        $job->name = $request->name;
        $job->save();

        $jobs = ParastiJob::all();
        return response()->json(['parasti' => $jobs], 200);
    }

    public function deleteDefault(Request $request){
        $job = DefaultJob::find($request->id);
        $job->delete();
    }

    public function deleteParasti(Request $request){
        $job = ParastiJob::find($request->id);
        $job->delete();
    }
}
