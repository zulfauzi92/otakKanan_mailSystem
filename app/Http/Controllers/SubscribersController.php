<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscribers;

class SubscribersController extends Controller
{
    public function index()
    {
        $subscribers = Subscribers::all();

        return response()->json(compact('subscribers'));
    }

    
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id' => 'required',
            'email' => 'required|string|email|max:255|unique:subscribers'
        ]);

        $subscribers = Subscribers::create([
            'user_id' => $request->get('user_id'),
            'email' => $request->get('email')
        ]);
        
        return response()->json(compact('subscribers'));
    }

   
    public function show($id)
    {
        $subscribers = Subscribers::find($id);
        
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

            if ($request->get('user_id') != null) {
                $subscribers->update([
                    'user_id' => $request->get('user_id')
                ]);
            }

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
