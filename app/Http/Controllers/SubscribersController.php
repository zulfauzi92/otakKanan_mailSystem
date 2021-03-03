<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class SubscribersController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $subscribers = DB::table('subscribers')
        ->where('user_id', 'like', $user->id)
        ->get();


        return response()->json(compact('subscribers'));
    }

    
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request,[
            'email' => 'required|string|email|max:255|unique:subscribers'
        ]);

        $subscribers = Subscribers::create([
            'user_id' => $user->id,
            'email' => $request->get('email')
        ]);
        
        return response()->json(compact('subscribers'));
    }

   
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $subscribers = DB::table('subscribers')
        ->where('user_id', 'like', $user->id)
        ->where('id', 'like', $id)
        ->first();
        
        if (empty($subscribers)) {
            return response()->json([ 'message' => "Data Not Found"]); 
        } else {
            return response()->json(compact('subscribers'));
        }
    }

 
    public function update(Request $request, $id)
    {
        $subscribers = Subscribers::find($id);

        if (empty($subscribers)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {


            if ($request->get('email') != null) {
                $subscribers->update([
                    'email' => $request->get('email')
                ]);
            }

            return response()->json([ 'message' => "Data Successfully Updated"]);  

        }
    }

    public function destroy($id)
    {
        $subscribers = Subscribers::find($id);

        if (empty($subscribers)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {
        
            $subscribers->delete();
            return response()->json([ 'message' => "Data Successfully Deleted"]);
            
        }

    }
}
