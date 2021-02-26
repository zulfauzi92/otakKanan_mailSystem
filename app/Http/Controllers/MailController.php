<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mail;
use App\Models\Campaign;
use App\Models\Subscribers;
use Illuminate\Support\Facades\DB;

class MailController extends Controller
{

    public function index() {

        $user = JWTAuth::parseToken()->authenticate();

        $mail = DB::table('mail')
        ->where('from_id', '=', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

        if (empty($mail)) {
        
            return response()->json(['status' => 'mail empty']);

        } else {

            $response['messageSent'] = array();

            foreach ($mail as $perItem) {
                
                $subscriber = Subscribers::find($perItem->to_id);
                $campaign = Campaign::find($perItem->campaign_id);

                $mail['mail_id'] = $perItem->id;
                $mail['to'] = $subscriber->email;
                $mail['subject'] = $campaign->subject;
                $mail['message'] = $campaign->message;
                $mail['created_at'] = $campaign->created_at;
                $mail['status'] = 'mail not empty';

                array_push($response['messageSent'], $mail);

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

            $detail['name'] = $user->name;
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

            return null;

        } else {

            $campaign = Campaign::find($mail->campaign_id);

            $campaign->delete();

        }


    }

}
