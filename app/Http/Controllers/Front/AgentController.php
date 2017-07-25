<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Models\Message;

class AgentController extends Controller
{
    /**
     * Gets Agents list page.
     *
     * @return view
     */
    public function getIndex()
    {
        $agents = User::where('role_id', '3')->paginate(6);
        return view('front.agent.index', [ 'agents' => $agents ]);
    }
   
    /**
     * Gets Agent detail page.
     *
     * @return view
     */
    public function getDetail($realestateagent, $agentId, $agencyName, $agentName)
    {
       $agent = User::where('role_id', '3')->find($agentId);
       
       if (empty($agent) || \App\Helpers\CommonHelper::cleanString($agent->name) != $agentName) {
           return abort('404');
       }
       
       return view('front.agent.detail', [ 'agent' => $agent, 'agentproperties' => $agent->properties()->paginate(30)]);
   }
   
   /**
    * Send contact message to Agent.
    *
    * @return view
    */
   public function postSendmessage(Request $request)
   {
       $validate = validator($request->all(), [
           'sender_name' => 'required',
           'sender_email' => 'required|email',
           'message' => 'required',
           'agent_id' => 'required|exists:users,id',
           'g-recaptcha-response' => 'required|recaptcha',
       ]);
     
       if ($validate->fails()) {
           return response([ 'errors' => $validate->getMessageBag()], 403);
       }
      
       $user = User::find($request->agent_id);
       
       $message = new Message;
       $message->entry_by = Auth::check() ? Auth::id() : null;
       $message->user_id = $request->agent_id;
       $message->sender_name = $request->sender_name;
       $message->sender_email = $request->sender_email;
       $message->to_email = $user->email;
       $message->message_text = $request->message;
       $message->type = 'agent_contact';
       $message->save();
       
       
       $data['agent'] = $user;
       $data['message'] = trans('common.ThanksContacting');
        
       return $data;
   }
}
