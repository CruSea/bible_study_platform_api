<?php

namespace App\Http\Controllers;

use App\BsYear;
use App\Category;
use Validator;
use Illuminate\Http\Request;
use Mockery\Exception;
use Excel;

class BSYearController extends Controller
{
    //
    /**
     * BSYearController constructor.
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

            $items = Category::orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $items], 200);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $item = BsYear::all()->orderBy('id', 'desc');
        return response()->json(['success' => true, 'result' => $item], 200);
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

        $new_item = new BsYear();
        $new_item->name =  isset($credentials['name']) ? $credentials['name'] : "";
        $new_item->description =  isset($credentials['description']) ? $credentials['description'] : "";
        $state = $new_item->save();

        if($state){
            return response()->json(["success" => true, "result"=>$new_item]);
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
        $item = BsYear::where('id', '=', $id)->first();
        if(!$item){
            return response()->json(["success" => false, "error"=>"BsYear not found"], 400);
        }
        return response()->json(["success" => true, "result"=>$item]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $item = BsYear::find($id);
        if(! $item){
            return response()->json(['status'=>false,'error'=> "BSYear not found"], 400);
        }

        $item->delete();
        return response()->json(["success" => true, "result"=>$item]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id){

        $item = BsYear::find($id);

        if(!$item){
            return response()->json(['status'=>false,'error'=> "BSYear not found"]);
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

        $item->name =  isset($credentials['name']) ? $credentials['name'] : "";
        $item->description =  isset($credentials['description']) ? $credentials['description'] : "";

        $item->update();
        return response()->json(["success" => true, "result"=>$item]);
    }
}
