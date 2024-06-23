<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ApiResponse;
use App\Http\Requests\ResumeRequest;
use App\Models\Resume;
use App\Http\Resources\ResumeResource;

class ResumeController extends Controller
{
    use ApiResponse;

    public function index(){
        $resumes = Resume::all();
        return ResumeResource::collection($resumes);
    }

    public function show($id)

    {
        $resume = Resume::find($id);
        if (! $resume) {
            return $this->errorResponse('res$resume not found', 404);
        }
    
        return $this->successResponse(new ResumeResource($resume));
    }
    public function store(ResumeRequest $request)
    {
        $validatedData = $request->validated();

    $file = $request->file('filename');

    // Generate a unique filename for the file
    $fileName = uniqid() . '_' . $file->getClientOriginalName();

    // Move the file to the storage directory
    $path = public_path('resumes/' . $fileName);
    $file->move(public_path('resumes'), $fileName);

    // Create a new Resume instance
    $resume = new Resume;
    $resume->user_id = $request->user_id;
    $resume->filename =$request->fileName;
    $resume->path = $path;
    $resume->original_filename = $file->getClientOriginalName();
    $resume->save();
        
        return $this->successResponse(new ResumeResource($resume), 'Resume uploded successfully');
    }

    public function update(ResumeRequest $request, $id)
    {
        $validatedData = $request->validated();

    $resume = Resume::findOrFail($id);

    // Update resume details
    $resume->user_id = $request->user_id ?? $resume->user_id; // Use existing user_id if not provided in request

    if ($request->hasFile('filename')) {
        $file = $request->file('filename');

        // Generate a unique filename for the file
        $fileName = uniqid() . '_' . $file->getClientOriginalName();

        // Move the file to the storage directory
        $path = public_path('resumes/' . $fileName);
        $file->move(public_path('resumes'), $fileName);
        // Delete old file
        if (file_exists($resume->path)) {
            unlink($resume->path);
        }

        // Update file details
        $resume->filename = $fileName;
        $resume->path = $path; // Update the path with the new file path
        $resume->original_filename = $file->getClientOriginalName();
    }

    $resume->save();

        return $this->successResponse(new ResumeResource($resume), 'Resume updated successfully');
    }

    public function destroy($id)
{
    // Find the resume by its ID
    $resume = Resume::findOrFail($id);

    // Get the path of the file
    $filePath = public_path($resume->path);

    // Check if the file exists and then delete it
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete the resume record from the database
    $resume->delete();

    return $this->successResponse(new ResumeResource($resume), 'Resume deleted successfully');
}
}