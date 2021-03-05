<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mail;
use App\Models\Campaign;
use App\Models\Subscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class MailController extends Controller
{

    public function index() {

        $user = JWTAuth::parseToken()->authenticate();

        $response['messageSent'] = array();
        $mail = DB::table('mail')
        ->where('from_id', '=', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

        if (empty($mail)) {
        
            return response()->json(['status' => 'mail empty']);

        } else {

            foreach ($mail as $perItem) {
                
                $subscriber = Subscribers::find($perItem->to_id);
                $campaign = Campaign::find($perItem->campaign_id);

                $email['mail_id'] = $perItem->id;
                $email['campaign_name'] = $campaign->name;
                $email['to'] = $subscriber->email;
                $email['subject'] = $campaign->subject;
                $email['message'] = $campaign->message;
                $email['created_at'] = $campaign->created_at;
                $email['status'] = 'mail not empty';

                array_push($response['messageSent'], $email);

            }

            return response()->json($response);

        }

    }


    public function show($id) {

        $mail = Mail::find($id);
        $user = JWTAuth::parseToken()->authenticate();

        if (empty($mail)) {

            return null;

        } else {

            $campaign = Campaign::find($mail->campaign_id);
            $subscriber = Subscribers::find($mail->to_id);

            $detail['campaign_name'] = $campaign->name;
            $detail['user_name'] = $user->name;
            $detail['email'] = $user->email;
            $detail['to'] = $subscriber->email;
            $detail['subject'] = $campaign->campaign;
            $detail['message'] = $campaign->message;

        }

        return response()->json(compact('detail'));

    }

    public function destroy($id) {
        
        $mail = Mail::find($id);

        if (empty($mail)) {

            return response()->json(['status' => 'mail can not be found']);

        } else {

            $campaign = Campaign::find($mail->campaign_id);

            $campaign->delete();

            return response()->json(['status' => 'mail has been deleted']);

        }


    }

}
