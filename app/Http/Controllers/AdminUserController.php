<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        // You can add filtering, sorting, and pagination logic here as needed
        if ($request->has('role')) {
            $query->where('role_id', $request->role);
        }
        // Name sorting
        $users = $query->orderBy('name', 'asc')->get();
        // Return the view with users data
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation for incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users', // Check for unique email in users table
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',

        ]);
        // 2. If validaition not pass, redirect back with errors (handled automatically by Laravel)
        // 3. If validation pass, code create new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Hash the password
            'role_id' => $validated['role_id'],
            'slug' => \Illuminate\Support\Str::slug($validated['name']) . '-' . rand(100, 999),
        ]);

        Subscription::create([
            'user_id' => $user->id, // Connect subscription to the newly created user
            'start_date' => Carbon::now(), // Today/now
            'end_date' => Carbon::now()->addMonth(), // Exactly one month later
            'type' => 'monthly',
            'price' => 0, // Set to 0 for now, later we can add a price field in the form
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)// Laravel by himself finds the user by slug because of the getRouteKeyName method in User model
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Validation - check incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'end_date' => 'nullable|date',
        ]);
        // Update user with validated data
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ]);

        if ($request->filled('end_date')) {
            $user->subscription()->updateOrCreate(
                ['user_id' => $user->id], // Condition to find existing subscription
                [
                    'end_date' => $validated['end_date'],
                    'start_date' => $user->subscription->start_date ?? now(), // Keep existing start_date or set to now 
                    'type' => 'monthly', // Default type
                    'price' => $user->subscription->price ?? 0,
                ]
            );
        }
        // If end_date is empty, delete subscription
        else {
            $user->subscription()->delete();
        }
        // redirect back to user list with success message
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}
