<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Mail;
use App\Models\Subscribers;
use App\Models\GroupSubscribers;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;


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

    public function storeChecklist(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $subscribers_temp['subscribers'] = array();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'subscribers.*' => 'required',
            'checkType' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => $validator->errors()->toJson()], 400);
        }

        if ($request->get('checkType') == 'group'){

            $receivers = $request->input('subscribers.*');

            foreach ($receivers as $id) {
                
                $group = DB::table('group_subscribers')
                ->where('group_id', '=', $id)
                ->get();

                foreach ($group as $item) {
                    $temp = Subscribers::find($item->subscribe_id);
                    array_push($subscribers_temp['subscribers'], $temp);
                    $groups_temp = Group::find($item->group_id);
                }

            }

            $subscribers_temp['group_name'] = $groups_temp->name;

        } else if ($request->get('checkType') == 'subscribers'){

            $receivers = $request->input('subscribers.*');

            foreach ($receivers as $id) {

                $subscriber = DB::table('subscribers')
                ->where('id', '=', $id)
                ->where('user_id', '=', $user->id)
                ->first();

                $temp = $subscriber;

                array_push($subscribers_temp['subscribers'], $temp);

            }

        } else {

            return response()->json(['status'=>'LOL']);

        }

        try{

            $campaign = Campaign::create([
                'name' => $request->get('name'),
                'subject' => $request->get('subject'),
                'message' => $request->get('message'),
                'user_id' => $user->id
            ]);

            $mail_temp['campaign_name'] = $campaign->name;

        }
        catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()]);
        }

        $batch = 0;
        $subscribers_temp['mail'] = array();
        $mail_temp['from'] = $user->email;
        $mail_temp['to'] = array();

        foreach($subscribers_temp['subscribers'] as $item) {        
            $batch++;

            if($batch > 1000){
                continue;
              
                return response()->json([ 'status' => "Max 1000 emails/campaign :)"]); 
            } 

            $details = [
                'name_campaign' => $campaign->name,
                'subject' => $campaign->subject,
                'body' => $campaign->message,
                'from' => $user->email,
                'name' => $user->name
            ];
    
            try{
                \Mail::to($item->email)->send(new \App\Mail\BasicMail($details));
            }
            catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()]);
            }

            try{

                $mail = Mail::create([
                    'from_id' => $user->id,
                    'to_id' => $item->id,
                    'campaign_id' => $campaign->id
                ]);
            }
            catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()]);
            }     
            
            array_push($mail_temp['to'], $item->email);

        }
    
        $batch = 0;
        $subscribers_temp['campaign'] = $campaign;
        $subscribers_temp['mail'] = $mail_temp;

        return response()->json($subscribers_temp);
    }
   
    // public function storeCompose(Request $request)
    // {
    //     $user = JWTAuth::parseToken()->authenticate();

    //     $this->validate($request,[
    //         'name' => 'required',
    //         'subject' => 'required',
    //         'message' => 'required',
    //         'receiver' => 'required|string|email|max:255'
    //     ]);

    //     $subscriber = DB::table('subscribers')
    //     ->where('email', 'like', $request->receiver)
    //     ->first();

    //     if (empty($subscriber)) {

    //         $receiver = Subscribers::create([
    //             'user_id' => $user->id,
    //             'email' => $request->receiver
    //         ]);

    //     } else { 

    //         $receiver = $subscriber;

    //     }

    //     try{

    //         $campaign = Campaign::create([
    //             'name' => $request->get('name'),
    //             'subject' => $request->get('subject'),
    //             'message' => $request->get('message'),
    //             'user_id' => $user->id
    //         ]);

    //     }
    //     catch(\Exception $e){
    //         return response()->json(['status'=>$e->getMessage()]);
    //     }

    //     try{

    //         $mail = Mail::create([
    //             'from_id' => $user->id,
    //             'to_id' => $receiver->id,
    //             'campaign_id' => $campaign->id
    //         ]);
    //     }
    //     catch(\Exception $e){
    //         return response()->json(['status'=>$e->getMessage()]);
    //     }

    //     $details = [
    //         'subject' => $request->get('subject'),
    //         'body' => $request->get('message'),
    //         'from' => $user->email,
    //         'name' => $user->name

    //     ];

    //     try{
    //         \Mail::to($request->get('receiver'))->send(new \App\Mail\BasicMail($details));
    //      }
    //      catch(\Exception $e){
    //         return response()->json(['status'=>$e->getMessage()]);
    //      }

        
               
    //     return response()->json(compact(['campaign', 'receiver', 'mail']));
    // }
    
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $campaign = DB::table('campaign')
        ->where('user_id', 'like', $user->id)
        ->where('id', 'like', $id)
        ->get();
        
        if (empty($campaign)) {
            return response()->json([ 'message' => "Data Not Found"]); 
        } else {
            return response()->json(compact('campaign'));
        }
    }


    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);

        if ($request->get('name') != null) {
            $campaign->update([
                'name' => $request->get('name')
            ]);
        }

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

        if (!empty($campaign)) {
            $campaign->delete();

            return response()->json([ 'message' => "Data Successfully Deleted"]);
        }    
    }
}
