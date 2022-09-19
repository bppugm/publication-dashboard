<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::latest()->filter(request(['search']))->paginate(10);

        return view('user.index')->with('users', $users);
    }

    public function create(){
        //
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validate(
            [
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]
        );

        $data['password'] = Hash::make($data['password']);

        $data = User::create($data);

        return $data;
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $update = $request->validate(
            [
                'name' => 'string|max:100',
                'email' => 'string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'string|min:8|confirmed',
            ]
        );

        if (isset($update['password'])) {
            $update['password'] = Hash::make($update['password']);
        }

        $user->update($update);

        return $user;

    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->noContent();
    }
}
