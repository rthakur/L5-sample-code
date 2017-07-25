<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Auth;

class ManageMessageController extends Controller
{
  public function index()
  {
      $data['contactedTo'] = [
          'agent_contact' => 'Agent',
          'contact_us' => 'MiParo Contact',
          'agency_contact' => 'Agency',
      ];
    
      $data['messages'] = Message::withTrashed()->where('type', '!=', 'contact_us')->paginate(20);
      return view('admin.message.index',$data);
  }
  
  public function getContactMessage()
  {
      Auth::user()->markAllMessagesAsRead();
      $data['messages'] = Message::where('type','contact_us')->paginate(20);
      return view('admin.message.contact_message',$data);
  }
}
