<?php

namespace App\Http\Controllers\api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class UserController extends Controller
{
  // all users
  public function index()
  {
    //$users=User::all();
    $users=User::latest()->paginate(10);
    return response()->json($users);
  }
  //access one user
  public function oneUser(User $user)
  {
    return response()->json($user);
  }
// update user
public function update(Request $request,User $user)
{
    $owner=$request->user();
    if($owner->id !== $user->id){
        abort(403,'Unauthorised');
    }
    // validation
    $request->validate([
        'name'=>'string|max:45',
        'email'=>'email'
    ]);
    $user->update([
        'name'=>$request->name ?? $user->name,
        'email'=>$request->email ?? $user->email,
    ]);

    // response
    return [
        'message'=>'user profile updated',
        'profile'=>$user
    ];
}
// soft delete
public function softDelete(Request $request, User $user)
{
    $admins=$request->user();
    if($admins->role !== 'admin' && $admins->role !== 'super_admin'){
        abort(403,'Unauthorised');
    }
    $user->update([
        'status'=>'suspended'
    ]);

    // response
    return [
        'message'=>'User suspended successifuuly',
        'user'=>$user
    ];
}

// unsuspend user
public function unsuspend(Request $request,User $user)
{
    // admins
    admins=$request->user();
    if($admins->role !== 'admin' && $admins->role !== 'super_admin'){
        abort(403,'Unauthorised');
    }
 
    // update
    $user->update([
        'status'=>'active'
    ]);
    // response
    return response()->json([
        'message'=>'User unsuspend successiful',
        'user'=>$user
    ]);
}


// view suspended users
public function viewSuspended()
{
    $suspended=User::where('status','suspended')->lates();
    return response()->json($suspended);
}

// change role to admin
public function makeAdmin(User $user)
{
   
    $user->update([
        'role'=>'admin'
    ]); 
    $role=$user->role;
  
    // response
    return [
        'message'=>'user role updated to admin',
        'role'=>$role
    ];
}
// demote role to user

public function demoteAdmin(User $user)
{
$user->update([
        'role'=>'user'
    ]); 

   // response
    $role=$user->role;
    return [
        'message'=>'Admin demoted to normal user ',
        'role'=>$role
    ];
}

}
