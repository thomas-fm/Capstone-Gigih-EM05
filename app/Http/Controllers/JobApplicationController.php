<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\CompanyProfile;
use Helper;
use Validator;
use JWTAuth;
use App\Models\JobApplication;
use Symfony\Component\HttpFoundation\Response;
use Whoops\Exception\ErrorException;
use App\Models\CourseRequirement;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;

class JobApplicationController extends Controller
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
    // COMPANY CONTROLLER
    public function fetchCompanyApplicants()
    {
        $jobs = $this->company_profile->jobs;
        $job_applicants = array();

        foreach($jobs as $i => $job)
        {
            $applicants = $job->job_applications()
                                ->with('user_profile', 'user_documents', 'enrollments')
                                ->get();
            $job['applicants'] = $applicants;
            $job_applicants[] = $job;
        }

        return Helper::SuccessResponse(true, $job_applicants, 'success', Response::HTTP_OK);
    }

    public function fetchCompanyApplicantsById(Request $request)
    {
        $job_application_id = $request->route('job_application_id');

        if (!isset($job_application_id)) return Helper::ErrorResponse("Required job application id param", Response::HTTP_BAD_REQUEST);

        $job_application = JobApplication::where('id', $job_application_id)
                        ->with('job', 'enrollments', 'user_documents')
                        ->get()
                        ->first();
        if (!$job_application) return Helper::ErrorResponse('Data not found.', Response::HTTP_NOT_FOUND);

        return Helper::SuccessResponse(true, $job_application, 'success', Response::HTTP_OK);
    }
    public function updateStatus(Request $request)
    {
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

        try {
            $application = JobApplication::find($input["application_id"]);

            if (is_null($application)) return Helper::ErrorResponse("Data not found", Response::HTTP_NOT_FOUND);

            $application->status = $input['status'];
            $application->save();

            return Helper::SuccessResponse(true, $application, "Updated succesfully", Response::HTTP_OK);
        } catch(QueryException $e) {
            $errorInfo = $e->getMessage();

            error_log($errorInfo);

            return Helper::ErrorResponse('Invalid request: '.$errorInfo, Response::HTTP_BAD_REQUEST);
        }

    }

    public function fetchApplicantsByFilter(Request $request)
    {
        $queries = $request->query();

        $validator = Validator::make($queries, [
            "status" => ['in:WITHDRAW,SUBMITTED,ON_REVIEW,ACCEPTED,REJECTED']
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $job_id = $request->query('job_id');
        $status = $request->query('status');

        $job_applicants = array();

        if (isset($job_id) && isset($status)) {
            $job_applicants = JobApplication::with('user_profile', 'user_documents', 'enrollments')
                                ->where([
                                    ["job_id", "=", $job_id],
                                    ["status", "=", $status]
                                ])
                                ->get();
        } else if (isset($job_id)) {
            $job_applicants = JobApplication::with('user_profile', 'user_documents', 'enrollments')
                                ->where("job_id", $job_id)
                                ->get();
        } else if (isset($status)) {
            $job_applicants = JobApplication::with('user_profile', 'user_documents', 'enrollments')
                                    ->where("status", $status)
                                    ->get();
        }

        return Helper::SuccessResponse(true, $job_applicants, 'success', Response::HTTP_OK);
    }

    // COMPANY CONTROLLER

    public function fetchApplicantDocuments(Request $request) {
        // application id
        $input = $request->only('application_id');

        $validator = Validator::make($input, [
            "application_id" => ['required', 'integer']
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $documents = JobApplication::find($input['application_id'])
                                ->user_documents()
                                ->get();

        return Helper::SuccessResponse(true, $documents, 'success', Response::HTTP_OK);
    }

    // todo
    public function fetchApplicantCourse(Request $request) {
        // applicant id
        $input = $request->only('application_id');

        $validator = Validator::make($input, [
            "application_id" => ['required', 'integer']
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $enrollments = JobApplication::find($input['application_id'])
                                ->enrollments()
                                ->get();

        return Helper::SuccessResponse(true, $enrollments, 'success', Response::HTTP_OK);
    }

    public function isUserEligible($job_id, $gradeMin = 0) {
        // payload: job_id
        $jobs = Job::where('id', $job_id);

        if ($jobs->first()) {
            $job = $jobs->first();
            $required_course = $job['courseRequirement'];

            if ($required_course) {
                $courses_enrollment = [];
                $course_requirements = $job->course_requirements()
                                            ->where('job_id', $job_id)
                                            ->get();

                foreach ($course_requirements as $course_requirement) {
                    $course_id = $course_requirement->course_id;

                    $enrollments = $this->user_profile->enrollments()
                                    ->where([
                                        ['status', '=', 'COMPLETED'],
                                        ['course_id', '=', $course_id],
                                        ['grade', '>=', $gradeMin]
                                        ])
                                    ->get();

                    $highest_grade = $enrollments->sortByDesc('grade')->first();

                    if (!$highest_grade) return [false, []];

                    $courses_enrollment[] = $highest_grade;
                }

                return [true, $courses_enrollment];
            } else {
                return [true, []];
            }
        }

        throw new ErrorException("Job not found", Response::HTTP_NOT_FOUND);
    }

    // USER CONTROLLER

    public function isJobApplicable(Request $request) {
        $input = $request->only('job_id');

        $validator = Validator::make($input, [
            "job_id" => ['required', 'integer']
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        list($can_apply, $enrolls) = $this->isUserEligible($input['job_id']);

        return Helper::SuccessResponse(true, $can_apply, 'success', Response::HTTP_OK);
    }

    public function addJobApplication(Request $request) {
        // payload: job_id, documentId[]
        // select document to input

        $input = $request->only('job_id', 'documents');

        $validator = Validator::make($input, [
            "job_id" => ['required', 'integer'],
            "documents" => ['required', 'array'],
            "documents.*" => ['integer']
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        list($user_can_apply, $enrollments_data) = $this->isUserEligible($input['job_id']);

        if (!$user_can_apply) return Helper::ErrorResponse('Can\'t apply to this job ', Response::HTTP_FORBIDDEN);

        try {
            $job_application = new JobApplication();
            $job_application->job()->associate($input['job_id']);

            $this->user_profile->job_applications()
                    ->save($job_application);
            $job_application->refresh();

            foreach ($input['documents'] as $document) {
                $job_application->user_documents()
                                ->attach($document);
            }

            foreach($enrollments_data as $data) {
                $id = $data->id;
                $job_application->enrollments()->attach($id);
            }
            // create application course
            $job_application->save();
            $job_application->refresh();

            return Helper::SuccessResponse(true, $job_application, 'success', Response::HTTP_OK);
        } catch(QueryException $e) {
            $errorInfo = $e->getMessage();

            error_log($errorInfo);

            return Helper::ErrorResponse('Invalid request: '.$errorInfo, Response::HTTP_BAD_REQUEST);
        }

    }

    public function cancelJobApplication(Request $request) {
        $input = $request->all();

        error_log(json_encode($input));
        $validator = Validator::make($input, [
            "job_application_id" => ['required', 'integer'],
            "status" => ['required', 'in:WITHDRAW'],
        ]);

        if($validator->fails())
        {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $job_application = $this->user_profile
                        ->job_applications()
                        ->where('id', $input['job_application_id'])
                        ->get()
                        ->first();
        if (!$job_application) return Helper::ErrorResponse('Data not found.', Response::HTTP_NOT_FOUND);

        try {
            $job_application->status = $input['status'];
            $job_application->save();
            $job_application->refresh();

            return Helper::SuccessResponse(true, $job_application, 'Withdraw from the application', Response::HTTP_OK);
        } catch(QueryException $e) {
            $errorInfo = $e->getMessage();

            error_log($errorInfo);

            return Helper::ErrorResponse('Invalid request: '.$errorInfo, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getApplicationDetailById(Request $request) {
        $job_application_id = $request->route('job_application_id');

        if (!isset($job_application_id)) return Helper::ErrorResponse('Status required', Response::HTTP_BAD_REQUEST);

        $job_application = $this->user_profile
                        ->job_applications()
                        ->where('id', $job_application_id)
                        ->with('job', 'enrollments', 'user_documents')
                        ->get()
                        ->first();
        if (!$job_application) return Helper::ErrorResponse('Data not found.', Response::HTTP_NOT_FOUND);

        return Helper::SuccessResponse(true, $job_application, 'success', Response::HTTP_OK);
    }

    public function getListOfAppliedJobs() {
        $job_application = $this->user_profile
                        ->job_applications()
                        ->with('job')
                        ->get();
        return Helper::SuccessResponse(true, $job_application, 'success', Response::HTTP_OK);
    }

    public function getListOfAppliedJobsWithFilter(Request $request) {
        $input = $request->only('status');

        if (!isset($input['status'])) return Helper::ErrorResponse('Status required', Response::HTTP_BAD_REQUEST);

        $job_application = $this->user_profile
                    ->job_applications()
                    ->where('status', $input['status'])
                    ->with('job')
                    ->get();

        return Helper::SuccessResponse(true, $job_application, 'success', Response::HTTP_OK);
    }
}
