<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Validator;
use App\Models\Category;
use App\Models\Job;
use Helper;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class UserDocumentController extends Controller
{
    protected $user;
    protected $user_profile;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->user_profile = UserProfile::where('user_id', $this->user->id)->first();
    }

    public function validateUser()
    {
        if (!isset($this->user_profile))
        {
            return Helper::ErrorResponse("Sorry, you need to complete your profile first", Response::HTTP_FORBIDDEN);
        }
    }
    public function addUserDocument(Request $request)
    {
        $this->validateUser();

        $input = $request->all();
        $validator = Validator::make($input, [
            'type' => ['required', 'in:CV,cv,CERTIFICATE,certificate,TRANSCRIPT,transcript,RESUME,resume,PORTOFOLIO,portofolio'],
            'url' => ['required_if:file,null', 'string', 'nullable'],
            'file' => ['required_if:file,null', 'file', 'nullabler']
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed: ".$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        return Helper::SuccessResponse(true, null, 'Succesfully added', Response::HTTP_OK);
    }

    public function fetchUserDocuments()
    {

    }
}
