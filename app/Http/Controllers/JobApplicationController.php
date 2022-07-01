<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyProfile;
use Helper;
use Validator;
use JWTAuth;
use App\Models\JobApplication;
use Symfony\Component\HttpFoundation\Response;

class JobApplicationController extends Controller
{
    protected $user;
    protected $company_profile;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->company_profile = CompanyProfile::where('user_id', $this->user->id)->first();
    }
    //
    public function validateCompany()
    {
        if (!isset($this->company_profile))
        {
            return Helper::ErrorResponse("Sorry, you need to complete your profile first", Response::HTTP_FORBIDDEN);
        }
    }

    public function fetchCompanyApplicants()
    {
        $this->validateCompany();

        $jobs = $this->company_profile->jobs;
        $job_applicants = array();

        foreach($jobs as $i => $job)
        {
            $applicants = $job->job_applications;

            foreach($applicants as $j => $applicant)
            {
                $applicants[$j]['profile_detail'] = $applicant->user_profile()->get(['user_profiles.fullname', 'user_profiles.email', 'user_profiles.phone']);
                $applicants[$j]['job_position'] = $job->position;
            }
            $job_applicants[] = $applicants;
        }

        return Helper::SuccessResponse(true, $job_applicants, 'success', Response::HTTP_OK);
    }

    public function updateStatus(Request $request)
    {
        $this->validateCompany();

        $input = $request->all();

        $validator = Validator::make($input, [
            "application_id" => ['required', 'integer'],
            "status" => ['required', 'in:on_review,accepted,rejected,ON_REVIEW,ACCEPTED,REJECTED']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $application = JobApplication::find($input["application_id"]);

        if (is_null($application)) return Helper::ErrorResponse("Data not found", Response::HTTP_NOT_FOUND);

        $application->status = $input['status'];
        $application->save();

        return Helper::SuccessResponse(true, $application, "Updated succesfully", Response::HTTP_OK);
    }

    public function fetchApplicantsByFilter(Request $request)
    {
        $this->validateCompany();

        $queries = $request->query();

        $validator = Validator::make($queries, [
            "status" => ['in:on_review,accepted,rejected,ON_REVIEW,ACCEPTED,REJECTED']
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $job_id = $request->query('job_id');
        $status = $request->query('status');

        $job_applicants = array();

        if (isset($job_id) && isset($status)) {
            $job_applicants = JobApplication::where([
                ["job_id", "=", $job_id],
                ["status", "=", $status]
            ])->get();
        } else if (isset($job_id)) {
            $job_applicants = JobApplication::where("job_id", $job_id)->get();
        } else if (isset($status)) {
            $job_applicants = JobApplication::where("status", $status)->get();
        }

        return Helper::SuccessResponse(true, $job_applicants, 'success', Response::HTTP_OK);
    }
}
