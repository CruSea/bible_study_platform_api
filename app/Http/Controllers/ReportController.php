<?php

namespace App\Http\Controllers;

use App\Member;
use App\Report;
use Illuminate\Http\Request;

class ReportController extends MainController
{

    /**
     * AttendantController constructor.
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
            'bs_id' => 'max:255 |required'
        ];

        $credentials = $request->only(
            'bs_id', 'email', 'phone', 'bs_time', 'full_name', 'imei', 'city', 'school', 'comment', 'leader_name'
        );

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json(['status' => false, 'error' => $error], 400);
        }

        $new_item = new Report();
        $new_item->bs_id =  isset($credentials['bs_id']) ? $credentials['bs_id'] : null;
        $new_item->bs_time =  isset($credentials['bs_time']) ? $credentials['bs_time'] : null;
        $new_item->full_name =  isset($credentials['full_name']) ? $credentials['full_name'] : null;
        $new_item->imei =  isset($credentials['imei']) ? $credentials['imei'] : null;
        $new_item->city =  isset($credentials['city']) ? $credentials['city'] : null;
        $new_item->school =  isset($credentials['school']) ? $credentials['school'] : null;
        $new_item->comment =  isset($credentials['comment']) ? $credentials['comment'] : null;
        $new_item->leader_name =  isset($credentials['leader_name']) ? $credentials['leader_name'] : null;

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
            $member = Member::where('phone', '=', $credentials['email']);
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
