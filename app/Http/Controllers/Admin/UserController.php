<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'digits:11'],
            'role' => ['required', 'in:admin,waiter,cashier,kitchen_staff'],
            'password' => ['required', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'unique:users,email,' . $user->id,
        ],
        'phone' => [
            'nullable',
            'digits:11',
            'regex:/^01[0-9]{9}$/',
        ],
        'role' => [
            'required',
            'in:admin,waiter,cashier,kitchen_staff',
        ],
    ]);

    $user->update($validated);

    if ($request->filled('password')) {
        $user->update([
            'password' => Hash::make($request->password)
        ]);
    }

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User updated successfully');
}
public function destroy(User $user)
{
   if ($user->getKey() === Auth::id()) {

        return redirect()
            ->route('admin.users.index')
            ->with('error', 'You cannot delete yourself.');
    }

    $user->delete();

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User deleted successfully');
}
}
