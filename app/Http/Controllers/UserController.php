<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('usuarios.index', compact('users'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|max:255',
            'password_confirmation' => 'required|same:password',
        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return redirect(url('admin/user'))->with('success', 'Usuario criado com sucesso');

    }

    public function edit(User $user)
    {
        return view('usuarios.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {

        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|nullable|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        return redirect(url('admin/user'))->with('success', 'Usuario Atualizado com sucesso');


    }
}
