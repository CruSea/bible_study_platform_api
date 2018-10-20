<?php

namespace App\Http\Controllers;

use App\BibleStudy;
use App\Member;
use Validator;
use Illuminate\Http\Request;
use Mockery\Exception;

class MembersController extends MainController
{
    //

    //

    /**
     * MembersController constructor.
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

            $users = Member::orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $users], 200);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }


    /**
     * Register new Attendant
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = [
            'full_name' => 'max:255 |required'
        ];

        $credentials = $request->only(
            'full_name', 'phone', 'email', 'age', 'sex', 'region', 'city', 'school', 'stream'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $new_item = new Member();
        $new_item->full_name =  isset($credentials['full_name']) ? $credentials['full_name'] : null;
        $new_item->phone =  isset($credentials['phone']) ? $credentials['phone'] : "";
        $new_item->email =  isset($credentials['email']) ? $credentials['email'] : null;
        $new_item->age =  isset($credentials['age']) ? $credentials['age'] : null;
        $new_item->sex =  isset($credentials['sex']) ? $credentials['sex'] : null;
        $new_item->region =  isset($credentials['region']) ? $credentials['region'] : null;
        $new_item->city =  isset($credentials['city']) ? $credentials['city'] : null;
        $new_item->school =  isset($credentials['school']) ? $credentials['school'] : null;
        $new_item->stream =  isset($credentials['stream']) ? $credentials['stream'] : null;

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
        $item = Member::where('id', '=', $id)->first();
        if(!$item){
            return response()->json(["success" => false, "error"=>"Item not found"], 400);
        }
        return response()->json(["success" => true, "result"=>$item]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $item = Member::find($id);
        if(! $item){
            return response()->json(['status'=>false,'error'=> "Item not found"], 400);
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

        $item = Member::find($id);

        if(!$item){
            return response()->json(['status'=>false,'error'=> "Item not found"]);
        }
        $rules = [
            'full_name' => 'max:255 |required'
        ];

        $credentials = $request->only(
            'full_name', 'phone', 'email', 'age', 'sex', 'region', 'city', 'school', 'stream'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $item->full_name =  isset($credentials['full_name']) ? $credentials['full_name'] : null;
        $item->phone =  isset($credentials['phone']) ? $credentials['phone'] : "";
        $item->email =  isset($credentials['email']) ? $credentials['email'] : null;
        $item->age =  isset($credentials['age']) ? $credentials['age'] : null;
        $item->sex =  isset($credentials['sex']) ? $credentials['sex'] : null;
        $item->region =  isset($credentials['region']) ? $credentials['region'] : null;
        $item->city =  isset($credentials['city']) ? $credentials['city'] : null;
        $item->school =  isset($credentials['school']) ? $credentials['school'] : null;
        $item->stream =  isset($credentials['stream']) ? $credentials['stream'] : null;

        $item->update();
        return response()->json(["success" => true, "result"=>$item]);
    }


}
