<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Subscribers;
use App\Models\GroupSubscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class GroupController extends Controller
{
    public function index()
    {

        $user = JWTAuth::parseToken()->authenticate();

        $group = DB::table('group')
        ->where('user_id', 'like', $user->id)
        ->get();

        if (empty($group)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {

            return response()->json(compact('group'));

        }

    }

    
    public function store(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request,[
            'name' => 'required|string|max:255|unique:group'
        ]);

        $group = Group::create([
            'user_id' => $user->id,
            'name' => $request->get('name')
        ]);
        
        return response()->json(compact('group'));
    }

   
    public function show($id)
    {

        $user = JWTAuth::parseToken()->authenticate();

        $group = DB::table('group')
        ->where('user_id', 'like', $user->id)
        ->where('id', 'like', $id)
        ->first();
        
        if (empty($group)) {

            return response()->json([ 'message' => "Data Not Found"]); 

        } else {

            

            $groupSubscribers = DB::table('group_subscribers')
            ->where('group_id', 'like', $group->id)
            ->get();

            $temp['group_name'] = $group->name;
            $temp['subscribers'] = array();

            foreach ($groupSubscribers as $item) {
                $subscribers = DB::table('subscribers')
                ->where('id', 'like', $item->subscribe_id)
                ->first();

                array_push($temp['subscribers'], $subscribers);
            }

            $response['group_subcribers'] = $temp;

            return response()->json($response);

        }

    }

 
    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {


            if ($request->get('name') != null) {
                $group->update([
                    'name' => $request->get('name')
                ]);
            }

            return response()->json([ 'message' => "Data Successfully Updated"]);  

        }
    }

    public function destroy($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {
        
            $group->delete();
            return response()->json([ 'message' => "Data Successfully Deleted"]);
            
        }

    }

}
