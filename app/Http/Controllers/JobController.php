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
use App\Models\UserProfile;
use App\Models\Course;
use Illuminate\Database\QueryException;

class JobController extends Controller
{
    protected $user;
    protected $company_profile;
    protected $user_profile;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->company_profile = CompanyProfile::where('user_id', $this->user->id)->first();
        $this->user_profile = UserProfile::where('user_id', $this->user->id)->first();
    }
    public function fetchAllJobs(Request $request)
    {
        $jobs = $this->company_profile
                    ->jobs()
                    ->with('categories', 'course_requirements')
                    ->get();

        // add categories and course requirements if true
        return Helper::SuccessResponse(true, $jobs, 'success', Response::HTTP_OK);
    }

    public function fetchJobById(Request $request)
    {
        // validate request
        $job_id = $request->route('jobId');

        if (!isset($job_id)) return Helper::ErrorResponse("Required job id param", Response::HTTP_BAD_REQUEST);

        // check if company profile exist
        $job = $this->company_profile->jobs()
                    ->where(['id' => $job_id])
                    ->with('categories', 'course_requirements')
                    ->first();

        return Helper::SuccessResponse(true, $job, 'success', Response::HTTP_OK);
    }

    public function createJob(Request $request)
    {
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
            'courseRequirement' => ['required', 'boolean'],
            "courses" => ["required_if:courseRequirement,==,true"]
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());

            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }
        try {
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

            if ($input['courseRequirement'])
            {
                foreach($input['courses'] as $course_id)
                {
                    $temp = Course::find($course_id);
                    if (!is_null($temp)) $job->course_requirements()->attach($course_id);
                }
            }

            $job->refresh();

            // get the data back
            $categories = $job->categories()->get(['categories.id', 'categories.name']);
            $job['categories'] = $categories;

            return Helper::SuccessResponse(true, $job, "success", Response::HTTP_CREATED);
        } catch (QueryException $e) {

        }

    }
    public function updateActiveStatus(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'job_id' => ['required', 'integer'],
            'active' => ['required', 'boolean']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed: ".$validator->errors()->first(), Response::HTTP_BAD_REQUEST);

        }

        $job = $this->company_profile->jobs()->find($input['job_id']);

        if (is_null($job)) return Helper::ErrorResponse("Data not found", Response::HTTP_BAD_REQUEST);

        try {
            $job->active = $input['active'];
            $job->save();

            // $job = $job->with('categories');

            $categories = $job->categories()->get(['categories.id', 'categories.name']);
            $job['categories'] = $categories;

            return Helper::SuccessResponse(true, $job, "success", Response::HTTP_OK);
        } catch (QueryException $e) {
            $errorInfo = $e->getMessage();

            error_log($errorInfo);

            return Helper::ErrorResponse('Invalid request: '.$errorInfo, Response::HTTP_BAD_REQUEST);
        }

    }
    public function deleteJobs(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'job_id' => ['required', 'integer'],
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed: ".$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $deleted = $this->company_profile->jobs()->where('id', $input['job_id'])->delete();

        if ($deleted) return Helper::SuccessResponse(true, null, "success", Response::HTTP_ACCEPTED);
        return Helper::ErrorResponse('Data not found', Response::HTTP_BAD_REQUEST);
    }

    public function getAllJobPaginate(Request $request)
    {
        $query = $request->all();

        $validator = Validator::make($query, [
            'limit' => ['required', 'integer'],
            'offset' => ['required', 'integer']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed: ".$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $jobs = Job::offset($query['offset'])->limit($query['limit'])->get();
        return Helper::SuccessResponse(true, $jobs, "success", Response::HTTP_ACCEPTED);
    }

    public function getJobPaginateWithOtherFilter(Request $request)
    {
        $query = $request->all();

        $validator = Validator::make($query, [
            'limit' => ['required', 'integer'],
            'offset' => ['required', 'integer'],
            'city' => ['string'],
            'province' => ['string'],
            'isRemote' => ['boolean'],
            'minSalary' => ['boolean'],
            'maxSalary' => ['boolean'],
            'type' => ['string'],
            'category_id' => ['integer']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed: ".$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $filter = [];

        if ($request->has('province')) $filter[] = ['province', '=', $query['province']];
        if ($request->has('city')) $filter[] = ['city', '=', $query['city']];
        if ($request->has('isRemote')) $filter[] = ['isRemote', '=', $query['isRemote']];
        if ($request->has('minSalary')) $filter[] = ['minSalary', '>=', $query['minSalary']];
        if ($request->has('maxSalary')) $filter[] = ['maxSalary', '<=', $query['maxSalary']];
        if ($request->has('type')) $filter[] = ['type', '=', $query['type']];

        $jobs = Job::where($filter)
                    ->offset($query['offset'])
                    ->limit($query['limit'])
                    ->get();

        if ($request->has('category_id')) {
            $filtered_jobs = [];

            foreach($jobs as $job) {
                $job_by_category = $job->categories()->where('category_id', $query['category_id']);
                error_log(json_encode($job_by_category));

                if ($job_by_category->first()) $filtered_jobs[] = $job_by_category->first();
            }

            return Helper::SuccessResponse(true, $filtered_jobs, "success", Response::HTTP_ACCEPTED);
        }

        return Helper::SuccessResponse(true, $jobs, "success", Response::HTTP_ACCEPTED);
    }
}
