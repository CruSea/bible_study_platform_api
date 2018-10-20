<?php

namespace App\Http\Controllers;

use App\Category;
use Validator;
use Illuminate\Http\Request;
use Mockery\Exception;
use Excel;

class CategoryController extends Controller
{
    //
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $pages = request()->only('len');
            $per_page = $pages != null ? (int)$pages['len'] : 10;

            if ($per_page > 50) {
                return response()->json(['success' => false, 'error' => 'Maximum page length is 50.'], 401);
            }

            $users = Category::orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $users], 200);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $users = Category::all()->orderBy('id', 'desc');
        return response()->json(['success' => true, 'result' => $users], 200);
    }

    /**
     * Add new Category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = [
            'name' => 'max:255 |required'
        ];

        $credentials = $request->only(
            'name', 'description'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $new_category = new Category();
        $new_category->name =  isset($credentials['name']) ? $credentials['name'] : "";
        $new_category->description =  isset($credentials['description']) ? $credentials['description'] : "";
        $state = $new_category->save();
        if($state){
            return response()->json(["success" => true, "result"=>$new_category]);
        }
        else{
            return response()->json(["success" => false, "error"=>"Something went wrong. Please try again"]);
        }
    }



    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::where('id', '=', $id)->first();
        if(!$category){
            return response()->json(["success" => false, "error"=>"Cagegory not found"], 400);
        }
        return response()->json(["success" => true, "result"=>$category]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $category = Category::find($id);
        if(! $category){
            return response()->json(['status'=>false,'error'=> "Category not found"], 400);
        }

        $category->delete();
        return response()->json(["success" => true, "result"=>$category]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id){

        $category = Category::find($id);

        if(!$category){
            return response()->json(['status'=>false,'error'=> "Category not found"]);
        }

        $rules = [
            'name' => 'max:255 |required'
        ];

        $credentials = $request->only(
            'name', 'description'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $category->name =  isset($credentials['name']) ? $credentials['name'] : "";
        $category->description =  isset($credentials['description']) ? $credentials['description'] : "";

        $category->update();
        return response()->json(["success" => true, "result"=>$category]);
    }

}
