<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns =  Campaign::orderBy('id','desc')->paginate(50);
        return view('admin.campaigns.index',['campaigns' => $campaigns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.campaigns.form');
    }
    
    public function generateRandomCampaignCode()
    {
        do {
            $shortLinkName = strtoupper(str_random(6));
            $checkExistingLink = Campaign::where('key', $shortLinkName)->first();
        } while (!empty($checkExistingLink));
        
        return $shortLinkName;
    }
    
    public function checkCode(Request $request)
    {
        if ($request->ajax()){
            $validate = validator($request->all(),[
                'code' => 'required|alpha_num|unique:campaigns,key'. ($request->id ? ','. $request->id : '')
            ]);
            
            if ($validate->fails()) {
                return response(['errors' => $validate->getMessageBag()], 403);
            }
            
            return 'Campaign code Available';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = validator($request->all(),[
            'name' => 'required|alpha',
            'trial_months' => 'required|integer|min:1|max:36',
            'end_date' => 'required|date_format:Y-m-d',
        ]);
        
        $campaign = $request->id ? Campaign::find($request->id) : new Campaign;

        if($campaign->key && $campaign->key != $request->campaign_code){
            $validate = validator($request->all(),[
                'campaign_code' => 'required|alpha_num|unique:campaigns,key'. ($request->id ? ','. $request->id : '')
            ]);
        }

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        $campaign->key = $request->campaign_code;
        $campaign->name = $request->name;
        $campaign->trial_months = $request->trial_months;
        $campaign->end_date = $request->end_date;
        $campaign->save();
        
        return redirect(SITE_LANG.'/admin/campaign');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::find($id);
        if(!$campaign) return back();
        return view('admin.campaigns.form', ['campaign' => $campaign]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        if($campaign) $campaign->delete();
        return back();
    }
}
