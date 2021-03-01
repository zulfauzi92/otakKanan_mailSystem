<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupSubscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class GroupSubscribersController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $groupSubscribers = DB::table('group_subscribers')
        ->where('user_id', 'like', $user->id)
        ->get();

        return response()->json(compact('groupSubscribers'));
    }

    
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id' => 'required',
            'group_id' => 'required',
            'subscribe_id' => 'required'
        ]);

        $groupSubscribers = GroupSubscribers::create([
            'user_id' => $request->get('user_id'),
            'group_id' => $request->get('group_id'),
            'subscribe_id' => $request->get('subscribe_id')
        ]);
        
        return response()->json(compact('groupSubscribers'));
    }

   
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $groupSubscribers = DB::table('group_subscribers')
        ->where('user_id', 'like', $user->id)
        ->where('id', 'like', $id)
        ->get();
        
        if (empty($groupSubscribers)) {

            return response()->json([ 'message' => "Data Not Found"]); 

        } else {

            return response()->json(compact('groupSubscribers'));

        }
    }

 
    public function update(Request $request, $id)
    {
        $groupSubscribers = GroupSubscribers::find($id);

        if (empty($groupSubscribers)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {

            if ($request->get('user_id') != null) {
                $groupSubscribers->update([
                    'user_id' => $request->get('user_id')
                ]);
            }

            if ($request->get('group_id') != null) {
                $groupSubscribers->update([
                    'group_id' => $request->get('group_id'),
                ]);
            }

            if ($request->get('subscribe_id') != null) {
                $groupSubscribers->update([
                    'subscribe_id' => $request->get('subscribe_id'),
                ]);
            }

            return response()->json([ 'message' => "Data Successfully Updated"]);  

        }
    }

    public function destroy($id)
    {
        $groupSubscribers = GroupSubscribers::find($id);

        if (empty($groupSubscribers)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {
        
            $groupSubscribers->delete();
            return response()->json([ 'message' => "Data Successfully Deleted"]);
            
        }

    }
}
