<?php

namespace App\Http\Controllers;

use App\BibleStudy;
use Validator;
use Illuminate\Http\Request;
use Mockery\Exception;

class BibleStudyController extends MainController
{
    //

    /**
     * BibleStudyController constructor.
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

            $users = BibleStudy::orderBy('id', 'desc')->with('year')->with('category')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $users], 200);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }

    public function search(){
        try {
            $pages = request()->only('len');
            $per_page = $pages != null ? (int)$pages['len'] : 10;

            if ($per_page > 50) {
                return response()->json(['success' => false, 'error' => 'Maximum page length is 50.'], 401);
            }


            $credentials = request()->only(
                'searchText'
            );

            $searchText = $credentials['searchText'];

            $attendants = Attendant::where('full_name', 'LIKE', "%$searchText%")
                ->orWhere('email', 'LIKE', "%$searchText%")
                ->orWhere('phone', 'LIKE', "%$searchText%")
                ->orWhere('city', 'LIKE', "%$searchText%")
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $attendants], 200);


        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }

    public function browseByCategory($id){
        try {
            $pages = request()->only('len');
            $per_page = $pages != null ? (int)$pages['len'] : 10;

            if ($per_page > 50) {
                return response()->json(['success' => false, 'error' => 'Maximum page length is 50.'], 401);
            }

            $credentials = request()->only(
                'category_id'
            );

            $category_id = $credentials['category_id'];
            $categories = BibleStudy::where('category_id', '=', "%$category_id%")
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $categories], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }


    public function browseByYear($id){
        try {
            $pages = request()->only('len');
            $per_page = $pages != null ? (int)$pages['len'] : 10;

            if ($per_page > 50) {
                return response()->json(['success' => false, 'error' => 'Maximum page length is 50.'], 401);
            }

            $credentials = request()->only(
                'year_id'
            );

            $category_id = $credentials['year_id'];
            $categories = BibleStudy::where('year_id', '=', "%$category_id%")
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $categories], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'Invalid credential used!!'], 401);
        }
    }

    public function browseByYearAndTerm(){
        try {
            $rules = [
                'term' => 'max:30 |required',
                'year_id' => 'max:30 |required |min:9'
            ];

            $credentials = request()->only(
                'term', 'year_id'
            );

            $validator = Validator::make($credentials, $rules);

            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'error' => $error], 400);
            }


            $pages = request()->only('len');
            $per_page = $pages != null ? (int)$pages['len'] : 10;

            if ($per_page > 50) {
                return response()->json(['success' => false, 'error' => 'Maximum page length is 50.'], 401);
            }


            $year_id = $credentials['year_id'];
            $term = $credentials['term'];
            $bs = BibleStudy::where('category_id', '=', "$year_id")
                ->where('term', '=', $term)
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            return response()->json(['success' => true, 'result' => $bs], 200);
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
            'bs_id' => 'max:255 |required',
            'term' => 'max:2 |required |min:1',
            'title' => 'required'
        ];

        $credentials = $request->only(
            'bs_id', 'term', 'category_id', 'year_id', 'bs_name', 'title', 'aim', 'verse', 'question', 'remark', 'further_info', 'tags'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $new_item = new BibleStudy();
        $new_item->bs_id =  isset($credentials['bs_id']) ? $credentials['bs_id'] : null;
        $new_item->term =  isset($credentials['term']) ? $credentials['term'] : "";
        $new_item->category_id =  isset($credentials['category_id']) ? $credentials['category_id'] : null;
        $new_item->year_id =  isset($credentials['year_id']) ? $credentials['year_id'] : null;
        $new_item->bs_name =  isset($credentials['bs_name']) ? $credentials['bs_name'] : null;
        $new_item->title =  isset($credentials['title']) ? $credentials['title'] : null;
        $new_item->aim =  isset($credentials['aim']) ? $credentials['aim'] : null;
        $new_item->verse =  isset($credentials['verse']) ? $credentials['verse'] : null;
        $new_item->question =  isset($credentials['question']) ? $credentials['question'] : null;
        $new_item->remark =  isset($credentials['remark']) ? $credentials['remark'] : null;
        $new_item->further_info =  isset($credentials['further_info']) ? $credentials['further_info'] : null;
        $new_item->tags =  isset($credentials['tags']) ? $credentials['tags'] : null;

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
        $item = BibleStudy::where('id', '=', $id)->first();
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
        $item = BibleStudy::find($id);
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

        $item = BibleStudy::find($id);

        if(!$item){
            return response()->json(['status'=>false,'error'=> "Item not found"]);
        }

        $rules = [
            'bs_id' => 'max:255 |required',
            'term' => 'max:2 |required |min:1',
            'title' => 'required'
        ];

        $credentials = $request->only(
            'bs_id', 'term', 'category_id', 'year_id', 'bs_name', 'title', 'aim', 'verse', 'question', 'remark', 'further_info', 'tags'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $item->bs_id =  isset($credentials['bs_id']) ? $credentials['bs_id'] : null;
        $item->term =  isset($credentials['term']) ? $credentials['term'] : "";
        $item->category_id =  isset($credentials['category_id']) ? $credentials['category_id'] : null;
        $item->year_id =  isset($credentials['year_id']) ? $credentials['year_id'] : null;
        $item->bs_name =  isset($credentials['bs_name']) ? $credentials['bs_name'] : null;
        $item->title =  isset($credentials['title']) ? $credentials['title'] : null;
        $item->aim =  isset($credentials['aim']) ? $credentials['aim'] : null;
        $item->verse =  isset($credentials['verse']) ? $credentials['verse'] : null;
        $item->question =  isset($credentials['question']) ? $credentials['question'] : null;
        $item->remark =  isset($credentials['remark']) ? $credentials['remark'] : null;
        $item->further_info =  isset($credentials['further_info']) ? $credentials['further_info'] : null;
        $item->tags =  isset($credentials['tags']) ? $credentials['tags'] : null;

        $item->update();
        return response()->json(["success" => true, "result"=>$item]);
    }
}
