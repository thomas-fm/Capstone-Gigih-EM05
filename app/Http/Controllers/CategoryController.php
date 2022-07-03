<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Helper;
use App\Models\Category;

class CategoryController extends Controller
{
    //
    public function addCategory(Request $request) {
        $input = $request->only('name');

        $validator = Validator::make($input, [
            "name" => ['required', 'string'],
        ]);

        if($validator->fails())
        {
            error_log($validator->errors());
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $category = new Category();
        $category->name = $input['name'];
        $category->save();
        $category->refresh();

        return Helper::SuccessResponse(true, $category, "Created succesfully", Response::HTTP_OK);
    }
}
