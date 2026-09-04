<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // all users
    public function index()
    {
        // $users=User::all();
        $users = User::latest()->paginate(10);

        return response()->json($users);
    }

    // access one user
    public function oneUser(User $user)
    {
        return response()->json($user);
    }

    // update user
    public function update(Request $request, User $user)
    {
        $owner = $request->user();
        if ($owner->id !== $user->id) {
            abort(403, 'Unauthorised');
        }
        // validation
        $request->validate([
            'name' => 'string|max:45',
            'email' => 'email',
        ]);
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ]);

        // response
        return [
            'message' => 'user profile updated',
            'profile' => $user,
        ];
    }

    // soft delete
    public function softDelete(Request $request, User $user)
    {
        $admins = $request->user();
        if ($admins->role !== 'admin' && $admins->role !== 'super_admin') {
            abort(403, 'Unauthorised');
        }
        $user->update([
            'status' => 'suspended',
        ]);

        // response
        return [
            'message' => 'User suspended successifuuly',
            'user' => $user,
        ];
    }

    // unsuspend user
    public function unsuspend(Request $request, User $user)
    {
        // admins
        $admins = $request->user();
        if ($admins->role !== 'admin' && $admins->role !== 'super_admin') {
            abort(403, 'Unauthorised');
        }

        // update
        $user->update([
            'status' => 'active',
        ]);

        // response
        return response()->json([
            'message' => 'User unsuspend successiful',
            'user' => $user,
        ]);
    }

    // view suspended users
    public function viewSuspended(Request $request)
    {
        $admins = $request->user();
        if ($admins->role !== 'admin' && $admins->role !== 'super_admin') {
            abort(403, 'Unauthorised');
        }

        // susupended users

        $users = User::where('status', 'suspended')->get();
        foreach ($users as $user) {
            if ($user->status !== 'suspended') {
                abort(405, 'different category');
            }
        }

        return response()->json($users);
    }

    // change role to admin
    public function makeAdmin(Request $request, User $user)
    {
        $admins = $request->user();
        if ($admins->role !== 'admin' && $admins->role !== 'super_admin') {
            abort(403, 'Unauthorised');
        }
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'inactive users can not be promoted',
            ], 405);
        }
        $user->update([
            'role' => 'admin',
        ]);
        $role = $user->role;

        // response
        return [
            'message' => 'user role updated to admin',
            'role' => $role,
        ];
    }
    // demote role to user

    public function demoteAdmin(Request $request, User $user)
    {
        $admins = $request->user();
        if ($admins->role !== 'admin' && $admins->role !== 'super_admin') {
            abort(403, 'Unauthorised');
        }
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'user is not an admin yet',
            ], 405);
        }
        $user->update([
            'role' => 'user',
        ]);

        // response
        $role = $user->role;

        return [
            'message' => 'Admin demoted to normal user ',
            'role' => $role,
        ];
    }
}
