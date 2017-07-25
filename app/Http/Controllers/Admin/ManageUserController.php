<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\Country;
use App\Models\Role;
use App\Models\Estateagency;
use Auth;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::select('users.id','users.name','users.email','users.created_at','role.name as rolename')
                        ->join('role',function($join){
                            $join->on('role.id','=','users.role_id');
                        });
        if ($request->s)
            $users = $users->where('users.email','like','%'.$request->s.'%')->orWhere('users.name','like','%'.$request->s.'%');
        
            if ($request->sort) {
                $users = $users->orderBy('users.'.$request->sort_type, $request->sort); 
                
                $data['sort_type'] = $request->sort_type ? $request->sort_type : 'name';                                   
                $data['sort'] = $request->sort == 'asc' ? 'desc' : 'asc';
            }
            
        $data['users'] = $users->where('users.id', '!=', Auth::id())->paginate(200);
        return view('admin.manageuser.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::all();
        $data['agencies'] = Estateagency::all();
        $data['roles'] = Role::orderBy('name')->get();
        return view('admin.manageuser.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = validator($request->all(), [
          'name'     => 'required', 
          'email'    => 'required|email|unique:users,email',
          'role_id'  => 'required|exists:role,id',
          'password' => 'required|min:6',
        ]);
      
        if($validate->fails())
          return back()->withErrors($validate)->withInput();
        
        $user = new User;
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->role_id    = $request->role_id;
        $user->agency_id  = $request->agency_id;
        $user->password   = bcrypt($request->password);
        $user->save();
        
        return redirect( SITE_LANG .'/admin/manageuser');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['user'] = User::find($id);
        $data['agencies'] = Estateagency::all();
        $data['roles'] = Role::all();
        return view('admin.manageuser.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = validator($request->all(), [
            'agency' => 'exists:estate_agencies,id',
        ]);
        
        if($validate->fails())
            return back()->withErrors($validate)->withInput();
        
        $user = User::find($id);
        if($request->agency) $user->agency_id = $request->agency;
        $user->save();
        return redirect( SITE_LANG .'/admin/manageuser');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->role->id != 1) $user->delete();
        return back();
    }
}
