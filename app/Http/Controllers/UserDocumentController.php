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
    protected $company_profile;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->company_profile = UserProfile::where('user_id', $this->user->id)->first();
    }

    public function addUserDocument()
    {

    }

    public function fetchUserDocuments()
    {

    }
}
