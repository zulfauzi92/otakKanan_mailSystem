<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Mail;
use App\Models\Subscribers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $campaigns = DB::table('campaign')
        ->where('user_id', 'like', $user->id)
        ->get();

        return response()->json(compact('campaigns'));
    }

    
   
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
            'receiver' => 'required|string|email|max:255'
        ]);

        $subscriber = DB::table('subscribers')
        ->where('email', 'like', $request->receiver)
        ->first();

        if (empty($subscriber)) {

            $receiver = Subscribers::create([
                'user_id' => $user->id,
                'email' => $request->receiver
            ]);

        } else { 

            $receiver = $subscriber;

        }

        $campaign = Campaign::create([
            'subject' => $request->get('subject'),
            'message' => $request->get('message'),
            'user_id' => $user->id
        ]);

        $mail = Mail::create([
            'from_id' => $user->id,
            'to_id' => $receiver->id,
            'campaign_id' => $campaign->id
        ]);
        
        return response()->json(compact(['campaign', 'receiver', 'mail']));
    }

    
    public function show($id)
    {
        $campaign = Campaign::find($id);
        
        if (empty($campaign)) {
            return response()->json([ 'message' => "Data Not Found"]); 
        } else {
            return response()->json(compact('campaign'));
        }
    }


    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);

        if ($request->get('subject') != null) {
            $campaign->update([
                'subject' => $request->get('subject')
            ]);
        }

        if ($request->get('message') != null) {
            $campaign->update([
                'message' => $request->get('message')
            ]);
        }

        // if ($request->get('track_click') != null) {
        //     $campaign->update([
        //         'track_click' => $request->get('track_click')
        //     ]);
        // }
        
        return response()->json([ 'message' => "Data Successfully Updated"]);  
    }

  
    public function destroy($id)
    {
        $campaign = Campaign::find($id);

        if ($campaign->delete()) {
            return response()->json([ 'message' => "Data Successfully Deleted"]);
        }    
    }
}
