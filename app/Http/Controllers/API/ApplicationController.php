<?php

namespace App\Http\Controllers\API;
use App\Trait\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $applications = Application::with(['application', 'user', 'resume'])->get();
        return ApplicationResource::collection($applications);
    }

    public function show($id)
    {
        $application = Application::with(['application', 'user', 'resume'])->find($id);
        
        if (!$application) {
            return $this->errorResponse('Application not found', 404);
        }

        return new ApplicationResource($application);
    }

    public function store(ApplicationRequest $request)

    {

    $application = new Application;
    $application->application_id = $request->application_id;
    $application->user_id = $request->user_id;
    $application->resume_id = $request->resume_id;
    $application->cover_letter = $request->cover_letter;
    $application->status = $request->status?? 'pending';

    $application->save();
    
    return $this->successResponse(new ApplicationResource($application), 'Application added successfully');
    }

    public function update(ApplicationRequest $request, $id)
    {
    $validatedData = $request->validated();
    $application = Application::find($id);
    
    if (!$application) {
        return $this->errorResponse('Application not found', 404);
    }

    $application->fill($validatedData);
    $application->save();
    
    return $this->successResponse(new ApplicationResource($application), 'application updated successfully');
}
public function destroy($id)
{
    $application = Application::find($id);

    if (!$application) {
        return response()->json(['error' => 'Application not found'], 404);
    }

    $application->delete();

    return $this->successResponse($application, 'Application deleted successfully');
}

    
}
