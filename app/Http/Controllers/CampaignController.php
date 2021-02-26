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
            'track_click' => 'required',
            'track_open' => 'required'
        ]);

        $campaign = Campaign::create([
            'subject' => $request->get('subject'),
            'message' => $request->get('message'),
            'track_click' => $request->get('track_click'),
            'track_open' => $request->get('track_open')
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
