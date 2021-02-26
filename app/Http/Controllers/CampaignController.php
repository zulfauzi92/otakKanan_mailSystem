<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Mail;
use App\Models\Subscribers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    
    public function index()
    {
        $campaigns = Campaign::all();

        return response()->json(compact('campaigns'));
    }

    
   
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
            'sender' => 'required|string|email|max:255'
        ]);

        $subscriber = DB::table('subscribers')
        ->where('email', 'like', $request->sender)
        ->first();

        if (empty($subscriber)) {

            $sender = Subscribers::create([
                'user_id' => $user->id,
                'email' => $request->sender
            ]);

        } else { 

            $sender = $subscriber;

        }

        $campaign = Campaign::create([
            'subject' => $request->get('subject'),
            'message' => $request->get('message'),
            'user_id' => $user->id
        ]);

        $mail = Mail::create([
            'from_id' => $user->id,
            'to_id' => $sender->id,
            'campaign_id' => $campaign->id
        ]);
        
        return response()->json(compact(['campaign', 'sender', 'mail']));
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
