<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Carbon\Carbon;

class Agent_notify_check_property extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If a property has been added via API, one hour after the property has been added, send an email to the agent, with a link that shows all properties that has been added';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $time = Carbon::now()->subHours(1);
      $PropertyAgents = Property::where('created_at','<',$time)
      ->where('email_notify',0)
      ->where('agent_checked',0)
      ->groupBy('agent_id')
      ->take(100)
      ->get();
      
      foreach ($PropertyAgents as $propertyAgent) {
        if($propertyAgent->agent) 
          $this->sendEmailNotification($propertyAgent->agent);
          
          Property::where('agent_id',$propertyAgent->agent_id)->update(['email_notify'=>1]);
      }
      
      echo 'Done!';
    }
    
    
    private function sendEmailNotification($user)
    {
      $subject = trans('emails.property_updated_subject');
      $emailStr = trans('emails.property_updated');
      $emailStr = str_replace('<user_name>', $user->name, $emailStr);
      $emailStr = str_replace('<link>', url('en/account/agent/myproperties/new'), $emailStr);
      $emailStr = nl2br($emailStr);
      
      \Mail::send('emails.agent.propertyupdated', [ 'emailContent' => $emailStr ], function($message) use($user, $subject){
         $message->to($user->email)->subject($subject);
       });
    }
}
