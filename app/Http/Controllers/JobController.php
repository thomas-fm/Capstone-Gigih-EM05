<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyProfile;
use Validator;
use App\Models\Course;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Helper;

class JobController extends Controller
{
    protected $user;
    protected $company_profile;
    public function __construct()
    {
        // TODO: Change this with authorization
        $this->user = User::find(3);
        $this->company_profile = CompanyProfile::where('user_id', $this->user->id)->first();
    }

    public function validate_company()
    {
        if (!isset($this->company_profile))
        {
            return Helper::ErrorResponse("Sorry, you need to complete your profile first", 400);
        }
    }

    public function fetch_all_jobs(Request $request)
    {
        $this->validate_company();

        $jobs = $this->company_profile->jobs;

        // add categories and course requirements if true
        foreach($jobs as $index => $job)
        {
            $categories = $job->categories()->get(['categories.id', 'categories.name']);
            $jobs[$index]['categories'] = $categories;
        }

        return Helper::SuccessResponse(true, 'success', $jobs);
    }

    public function fetch_job_by_id(Request $request)
    {
        $this->validate_company();

        // validate request
        $job_id = $request->route('jobId');

        if (!isset($job_id)) return Helper::ErrorResponse("Required job id param", 400);

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

        return Helper::SuccessResponse(true, 'success', $job);
    }

    public function create_job(Request $request)
    {
        $this->validate_company();

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
            'categories' => ['required']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Valudation failed", 400);
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

        return Helper::SuccessResponse(true, "success", $job);
    }
    public function update_active_status(Request $request)
    {
        $this->validate_company();

        $input = $request->all();
        $validator = Validator::make($input, [
            'job_id' => ['required', 'integer'],
            'active' => ['required', 'boolean']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed", 400);
        }

        $job = $this->company_profile->jobs()->find($input['job_id']);

        if (is_null($job)) return Helper::ErrorResponse("Data not found", 404);

        $job->active = $input['active'];
        $job->save();

        $categories = $job->categories()->get(['categories.id', 'categories.name']);
        $job['categories'] = $categories;

        return Helper::SuccessResponse(true, "success", $job);
    }
    public function delete_jobs(Request $request)
    {
        $this->validate_company();

        $input = $request->all();
        $validator = Validator::make($input, [
            'job_id' => ['required', 'integer'],
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed", 400);
        }

        $deleted = $this->company_profile->jobs()->where('id', $input['job_id'])->delete();

        if ($deleted) return Helper::SuccessResponse(true, "success", null);
        return Helper::ErrorResponse('Data not found', 404);
    }
}
