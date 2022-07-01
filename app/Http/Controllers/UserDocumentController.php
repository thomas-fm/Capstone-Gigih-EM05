<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserDocument;
use Validator;
use App\Models\Category;
use App\Models\Job;
use Helper;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Storage;
use Google_Service_Exception;
use Google_Exception;

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

        $input = $request->only('type', 'url', 'file');

        $validator = Validator::make($input, [
            'type' => ['required', 'in:CV,cv,CERTIFICATE,certificate,TRANSCRIPT,transcript,RESUME,resume,PORTOFOLIO,portofolio'],
            'url' => ['prohibited_unless:file,null', 'required_without:file', 'string'],
            'file' => ['prohibited_unless:url,null', 'required_without:url', 'file']
        ]);

        if($validator->fails()) {
            error_log($validator->errors());
            return Helper::ErrorResponse("Validation failed: ".$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        if (isset($input['url'])) {
            $document = new UserDocument();
            $document['type'] = $input['type'];
            $document['url'] = $input['url'];

            $this->user_profile->user_documents()->save($document);

            $document->refresh();

            return Helper::SuccessResponse(true, $document, 'Succesfully added', Response::HTTP_OK);

        } else {
            try {
                if ($request->file('file')->isValid()) {
                    $file = $request->file('file');
                    $fileContent = $request->file('file')->getContent();

                    $filename = $file->getRealPath();

                    error_log($filename);

                    $filename = $file->getClientOriginalName();
                    Storage::disk('google')->put($filename, $fileContent);
                    $details = Storage::disk("google")->getMetadata($filename);

                    $document = new UserDocument();
                    $document['type'] = $input['type'];
                    $document['url'] = 'https://drive.google.com/file/d/'.$details['path'];
                    $document['filename'] = $filename;
                    $document['isUploaded'] = true;

                    $this->user_profile->user_documents()->save($document);

                    $document->refresh();

                    return Helper::SuccessResponse(true, $document, 'Succesfully added', Response::HTTP_OK);
                }

                return Helper::ErrorResponse('Failed to read file', Response::HTTP_BAD_REQUEST);
            } catch (Google_Exception $exception) {
                error_log($exception->getMessage());
                return Helper::ErrorResponse('Google server error', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function fetchUserDocuments()
    {
        $this->validateUser();

        $documents = $this->user_profile->user_documents;

        return Helper::SuccessResponse(true, $documents, 'Success', Response::HTTP_OK);
    }

    public function fetchUserDocumentById(Request $request)
    {
        $this->validateUser();

        $document_id = $request->route('document_id');

        $document = $this->user_profile->user_documents()
                                        ->where('id', $document_id)
                                        ->get();

        if (isset($document)) {
            return Helper::SuccessResponse(true, $document, 'Success', Response::HTTP_OK);
        }
        return Helper::ErrorResponse('Data not found', Response::HTTP_NOT_FOUND);
    }

    public function deleteUserDocumentById(Request $request)
    {
        $this->validateUser();

        $document_id = $request->route('document_id');

        $documents = $this->user_profile->user_documents()
                                        ->where('id', $document_id)
                                        ->get();

        if ($documents) {
            if ($documents->first()['isUploaded']) {
                // delete from drive
                try {
                    $url = $documents->first()['url'];
                    $file_id = explode("https://drive.google.com/file/d/", $url)[1];

                    $deleted = Storage::disk('google')->delete($file_id);

                    if (!$deleted) throw new Google_Exception("File not found");
                } catch (Google_Exception $e) {
                    return Helper::ErrorResponse('Failed to delete. '.$e->getMessage(), Response::HTTP_NOT_FOUND);
                }
            }

            $deleted_document = $documents->first()->delete();

            if ($deleted_document) {
                return Helper::SuccessResponse(true, null, 'Success', Response::HTTP_OK);
            }
        }

        return Helper::ErrorResponse('Failed to delete', Response::HTTP_NOT_FOUND);
    }
}
