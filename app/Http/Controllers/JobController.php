<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyProfile;
use Validator;
use App\Models\Category;
use App\Models\Job;
use Helper;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    protected $user;
    protected $company_profile;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->company_profile = CompanyProfile::where('user_id', $this->user->id)->first();
    }

    public function validateCompany()
    {
        if (!isset($this->company_profile))
        {
            return Helper::ErrorResponse("Sorry, you need to complete your profile first", Response::HTTP_FORBIDDEN);
        }
    }

    public function fetchAllJobs(Request $request)
    {
        $this->validateCompany();

        $jobs = $this->company_profile->jobs;

        // add categories and course requirements if true
        foreach($jobs as $index => $job)
        {
            $categories = $job->categories()->get(['categories.id', 'categories.name']);
            $jobs[$index]['categories'] = $categories;
        }

        return Helper::SuccessResponse(true, $jobs, 'success', Response::HTTP_OK);
    }

    public function fetchJobById(Request $request)
    {
        $this->validateCompany();

        // validate request
        $job_id = $request->route('jobId');

        if (!isset($job_id)) return Helper::ErrorResponse("Required job id param", Response::HTTP_BAD_REQUEST);

        // check if company profile exist
        $job = $this->company_profile->jobs()
                    ->where(['id' => $job_id])
                    ->first();

        // add categories and course requirements if true
        if (isset($job))
        {
            $categories = $job->categories()->get(['categories.id', 'categories.name']);
            $job['categories'] = $categories;
        }

        return Helper::SuccessResponse(true, $job, 'success', Response::HTTP_OK);
    }

    public function createJob(Request $request)
    {
        $this->validateCompany();

        $input = $request->all();
        $validator = Validator::make($input, [
            'position' => ['required', 'string'],
            'type' => ['required', 'in:fulltime,intern,parttime,freelance'],
            'description' => ['required', 'string'],
            'isRemote' => ['required', 'boolean'],
            'city' => ['string'],
            'province' => ['string'],
            'minSalary' => ['integer', 'gt:0'],
            'maxSalary' => ['integer', 'gt:0'],
            'expired' => ['required', 'string'],
            'categories' => ['required'],
            'courseRequirement' => ['required', 'boolean']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed", Response::HTTP_BAD_REQUEST);
        }

        // check if category exist
        $job = new Job();
        $job->position = $input['position'];
        $job->type = $input['type'];
        $job->description = $input['description'];
        $job->isRemote = $input['isRemote'];
        $job->city = $input['city'];
        $job->province = $input['province'];
        $job->minSalary = $input['minSalary'];
        $job->maxSalary = $input['maxSalary'];
        $job->expired = $input['expired'];
        $job->courseRequirement = $input['courseRequirement'];

        $this->company_profile->jobs()->save($job);

        $job->refresh();

        if (isset($input['categories']))
        {
            foreach($input['categories'] as $categoryId)
            {
                $temp = Category::find($categoryId);
                if (!is_null($temp)) $job->categories()->attach($categoryId);
            }
        }
        $job->refresh();

        // get the data back
        $categories = $job->categories()->get(['categories.id', 'categories.name']);
        $job['categories'] = $categories;

        return Helper::SuccessResponse(true, $job, "success", Response::HTTP_CREATED);
    }
    public function updateActiveStatus(Request $request)
    {
        $this->validateCompany();

        $input = $request->all();
        $validator = Validator::make($input, [
            'job_id' => ['required', 'integer'],
            'active' => ['required', 'boolean']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed", Response::HTTP_BAD_REQUEST);
        }

        $job = $this->company_profile->jobs()->find($input['job_id']);

        if (is_null($job)) return Helper::ErrorResponse("Data not found", Response::HTTP_BAD_REQUEST);

        $job->active = $input['active'];
        $job->save();

        $categories = $job->categories()->get(['categories.id', 'categories.name']);
        $job['categories'] = $categories;

        return Helper::SuccessResponse(true, $job, "success", Response::HTTP_OK);
    }
    public function deleteJobs(Request $request)
    {
        $this->validateCompany();

        $input = $request->all();
        $validator = Validator::make($input, [
            'job_id' => ['required', 'integer'],
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed", Response::HTTP_BAD_REQUEST);
        }

        $deleted = $this->company_profile->jobs()->where('id', $input['job_id'])->delete();

        if ($deleted) return Helper::SuccessResponse(true, null, "success", Response::HTTP_ACCEPTED);
        return Helper::ErrorResponse('Data not found', Response::HTTP_BAD_REQUEST);
    }
}
