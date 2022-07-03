<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Validator;
use App\Models\Category;
use App\Models\Job;
use Helper;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserProfile;
use App\Models\Course;
use Illuminate\Database\QueryException;
class CompanyProfileController extends Controller
{
    protected $user;
    protected $company_profile;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->company_profile = CompanyProfile::where('user_id', $this->user->id)->first();
    }

    public function addCompanyProfile(Request $request) {
        $input = $request->only('name', 'description', 'city', 'province');

        $validator = Validator::make($input, [
            "name" => ['required', 'string'],
            "description" => ['required', 'string'],
            "city" => ['required', 'string'],
            "province" => ['required', 'string'],
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $company_profile = new CompanyProfile($input);
        $this->user->company_profiles()->save($company_profile);
        $company_profile->refresh();

        return Helper::SuccessResponse(true, $company_profile, 'success', Response::HTTP_OK);
    }

    public function updateCompanyProfile(Request $request) {
        $input = $request->only('name', 'description', 'city', 'province');

        $validator = Validator::make($input, [
            "name" => ['string'],
            "description" => ['string'],
            "city" => ['string'],
            "province" => ['string'],
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('name')) $this->company_profile->name = $input['name'];
        if ($request->has('description')) $this->company_profile->description = $input['description'];
        if ($request->has('city')) $this->company_profile->city = $input['city'];
        if ($request->has('province')) $this->company_profile->province = $input['province'];

        $this->company_profile->save();
        $this->company_profile->refresh();

        return Helper::SuccessResponse(true, $this->company_profile, 'success', Response::HTTP_OK);
    }
}
