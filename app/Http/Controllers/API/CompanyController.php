<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ApiResponse;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Http\Resources\CompanyResource;
class CompanyController extends Controller
{
    use ApiResponse;

    public function index(){
        $companies = Company::with('jobs')->get();
        return CompanyResource::collection($companies);
    }

    public function show($id)

    {
        $company = Company::with('jobs')->find($id);
    
        if (! $company) {
            return $this->errorResponse('company not found', 404);
        }
    
        return $this->successResponse(new CompanyResource($company));
    }

    public function store(CompanyRequest $request)
    {
    $company = new Company;
    $company->user_id = $request->user_id;
    $company->name = $request->name;
    $company->industry = $request->industry;
    $company->location = $request->location;
    $company->description = $request->description;


    $company->save();
    
    return $this->successResponse(new CompanyResource($company), 'Company added successfully');
    }

    public function update(CompanyRequest $request, $id)
{
    $validatedData = $request->validated();
    $company = Company::find($id);
    
    if (!$company) {
        return response()->json(['error' => 'Company not found'], 404);
    }

    $company->fill($validatedData);
    $company->save();
    
    return $this->successResponse(new CompanyResource($company), 'Company updated successfully');
}

//Will delete the company with job related to this
public function destroy($id)
{
    $company = Company::find($id);

    if (!$company) {
        return response()->json(['error' => 'Company not found'], 404);
    }

    $company->delete();

    return $this->successResponse($company, 'Company deleted successfully');
}


}



