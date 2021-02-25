<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        $group = Group::all();

        return response()->json(compact('group'));
    }

    
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id' => 'required',
            'name' => 'required|string|max:255|unique:group'
        ]);

        $group = Group::create([
            'user_id' => $request->get('user_id'),
            'name' => $request->get('name')
        ]);
        
        return response()->json(compact('group'));
    }

   
    public function show($id)
    {
        $group = Group::find($id);
        
        if (empty($group)) {
            return response()->json([ 'message' => "Data Not Found"]); 
        } else {
            return response()->json(compact('group'));
        }
    }

 
    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            
            return response()->json([ 'message' => "Data Not Found"]); 

        } else {

            if ($request->get('user_id') != null) {
                $group->update([
                    'user_id' => $request->get('user_id')
                ]);
            }

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
