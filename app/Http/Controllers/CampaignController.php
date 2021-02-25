<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::all();

        return response()->json(compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
            'email_sender' => 'required',
            'email_target' => 'required'
        ]);

        $campaign = Campaign::create([
            'subject' => $request->get('subject'),
            'message' => $request->get('message'),
            'email_sender' => $request->get('email_sender'),
            'email_target' => $request->get('email_target')
        ]);
        
        return response()->json($campaign);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);
        
        if (empty($campaign)) {
            return response()->json([ 'message' => "Data Not Found"]); 
        } else {
            return response()->json(compact('campaign'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
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

        if ($request->get('email_sender') != null) {
            $campaign->update([
                'email_sender' => $request->get('email_sender')
            ]);
        }

        if ($request->get('email_target') != null) {
            $campaign->update([
                'email_target' => $request->get('email_target')
            ]);
        }

        if ($request->get('track_click') != null) {
            $campaign->update([
                'track_click' => $request->get('track_click')
            ]);
        }
        
        return response()->json([ 'message' => "Data Successfully Updated"]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);

        if ($campaign->delete()) {
            return response()->json([ 'message' => "Data Successfully Deleted"]);
        }    
    }
}
