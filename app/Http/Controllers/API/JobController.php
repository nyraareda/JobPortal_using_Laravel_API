<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ApiResponse;
use App\Http\Requests\JobRequest;
use App\Models\Job;
use App\Http\Resources\JobResource;
use Carbon\Carbon;

class JobController extends Controller
{
    use ApiResponse;

    public function index(){
        $jobs = Job::all();
        return JobResource::collection($jobs);
    }

    public function show($id)

    {
    
        if (! $job) {
            return $this->errorResponse('job not found', 404);
        }
    
        return $this->successResponse(new JobResource($job));
    }

    public function store(JobRequest $request)

    {

    $job = new job;
    $job->company_id = $request->company_id;
    $job->title = $request->title;
    $job->description = $request->description;
    $job->location = $request->location;
    $job->salary = $request->salary;
    $job->requirements = $request->requirements;
    $job->posted_at = $request->posted_at ?? Carbon::now();

    $job->save();
    
    return $this->successResponse(new JobResource($job), 'job added successfully');
    }

    public function update(JobRequest $request, $id)
    {
    $validatedData = $request->validated();
    $job = Job::find($id);
    
    if (!$job) {
        return response()->json(['error' => 'job not found'], 404);
    }

    $job->fill($validatedData);
    $job->save();
    
    return $this->successResponse(new JobResource($job), 'job updated successfully');
}

public function destroy($id)
{
    $job = Job::find($id);

    if (!$job) {
        return response()->json(['error' => 'job not found'], 404);
    }

    $job->delete();

    return $this->successResponse($job, 'job deleted successfully');
}


}
