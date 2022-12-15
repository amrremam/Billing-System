<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Illuminate\support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

public function index(Request $request)
{
$user = User::orderBy('id','DESC')->paginate(5);
return view('User.index',compact('user'))
->with('i', ($request->input('page', 1) - 1) * 5);
}



public function create()
{
$roles = Role::pluck('name','name')->all();
return view('user.create',compact('roles'));
}



public function store(Request $request)
{
$this->validate($request, [
'name' => 'required',
'email' => 'required|email|unique:users,email',
'password' => 'required|same:confirm-password',
'roles_name' => 'required'
]);
$input = $request->all();
$input['password'] = Hash::make($input['password']);
$user = User::create($input);
$user->assignRole($request->input('roles'));
return redirect()->route('users.index')
->with('success','User created successfully');
}




public function show($id)
{
$user = User::find($id);
return view('users.show',compact('user'));
}



public function edit($id)
{
$user = User::find($id);
$roles = Role::pluck('name','name')->all();
$userRole = $user->roles->pluck('name','name')->all();
return view('User.edit',compact('user','roles','userRole'));
}




public function update(Request $request, $id)
{
$this->validate($request, [
'name' => 'required',
'email' => 'required|email|unique:users,email,'.$id,
'password' => 'same:confirm-password',
'roles' => 'required'
]);
$input = $request->all();
if(!empty($input['password'])){
$input['password'] = Hash::make($input['password']);
}else{
$input = Arr::except($input,array('password'));
}
$user = User::find($id);
$user->update($input);
DB::table('model_has_roles')->where('model_id',$id)->delete();
$user->assignRole($request->input('roles'));
return redirect()->route('users.index')
->with('success','User updated successfully');
}




public function destroy($id)
{
User::find($id)->delete($id);
return redirect()->route('users.index')
->with('success','User deleted successfully');
}
}