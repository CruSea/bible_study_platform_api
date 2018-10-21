<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Member;
use Validator;
use Illuminate\Http\Request;
use Mockery\Exception;

class FeedbackController extends Controller
{
    //

    /**
     * FeedBackController constructor.
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

            $feedback = Feedback::orderBy('id', 'desc')
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
        ];

        $credentials = $request->only(
            '', 'email', 'phone', 'feedback'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $new_item = new Feedback();
        $new_item->feedback =  isset($credentials['feedback']) ? $credentials['feedback'] : null;

        if(isset($credentials['email'])){
            $member = Member::where('email', '=', $credentials['email']);
            if($member instanceof Member){
                $new_item->member_id = $member->id;
            } else{
                $new_member = new Member();
                $new_member->email = $credentials['email'];
                $new_member->full_name = $credentials['full_name'];
                $new_member->city = isset($credentials['city']) ? $credentials['city'] : null;
                $state = $new_member->save();
                if($state){
                    $new_item->member_id = $new_member->id;
                }
                else{
                    $new_item->member_id = null;
                }
            }
        }
        else if(isset($credentials['phone'])){
            $member = Member::where('phone', '=', $credentials['phone']);
            if($member instanceof Member){
                $new_item->member_id = $member->id;
            }
            else{
                $new_member = new Member();
                $new_member->phone = $credentials['phone'];
                $new_member->full_name = $credentials['full_name'];
                $new_member->city = isset($credentials['city']) ? $credentials['city'] : null;
                $state = $new_member->save();
                if($state){
                    $new_item->member_id = $new_member->id;
                }
                else{
                    $new_item->member_id = null;
                }
            }
        }
        else{
            $new_item->member_id = null;
        }

        $state = $new_item->save();
        if($state){
            return response()->json(["success" => true, "result"=>$new_item]);
        }
        else{
            return response()->json(["success" => false, "error"=>"Something went wrong. Please try again"]);
        }
    }


}
