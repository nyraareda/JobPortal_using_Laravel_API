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

    $fileName = uniqid() . '_' . $file->getClientOriginalName();

    $path = public_path('resumes/' . $fileName);
    $file->move(public_path('resumes'), $fileName);

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

    $resume->user_id = $request->user_id ?? $resume->user_id;

    if ($request->hasFile('filename')) {
        $file = $request->file('filename');

        $fileName = uniqid() . '_' . $file->getClientOriginalName();

        $path = public_path('resumes/' . $fileName);
        $file->move(public_path('resumes'), $fileName);

        if (file_exists($resume->path)) {
            unlink($resume->path);
        }


        $resume->filename = $fileName;
        $resume->path = $path;
        $resume->original_filename = $file->getClientOriginalName();
    }

    $resume->save();

        return $this->successResponse(new ResumeResource($resume), 'Resume updated successfully');
    }

    public function destroy($id)
{
    $resume = Resume::findOrFail($id);

    $filePath = public_path($resume->path);

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $resume->delete();

    return $this->successResponse(new ResumeResource($resume), 'Resume deleted successfully');
}
}