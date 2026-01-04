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
        $query = User::query()->select('users.*') // Select all user fields 
            ->leftJoin('subscriptions', 'users.id', '=', 'subscriptions.user_id')->with(['role', 'subscriptions']);
        // You can add filtering, sorting, and pagination logic here as needed
        if ($request->filled('search')) {
            $query->where('users.name', 'like', '%' . $request->search . '%')
                ->orWhere('users.email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('users.role_id', $request->role);
        }

        if ($request->get('sort') === 'subscriptions') {
            $direction = $request->get('direction') === 'desc' ? 'DESC' : 'ASC';

            // Sorting by subscription end_date, placing users without subscriptions at the end
            $query->orderByRaw("subscriptions.end_date $direction");
        } else {
            $query->orderBy('users.name', 'asc');
        }

        $stats = [
            'total' => User::count(),
            'active' => User::whereHas('role', function ($q) {
                $q->whereIn('name', ['admin', 'trainer']);
            })->orWhereHas('subscriptions', function ($q) {
                $q->where('end_date', '>=', now()->startOfDay());
            })->count(),
            'expired' => User::whereHas('role', function ($q) {
                $q->whereNotIn('name', ['admin', 'trainer']);
            })->where(function ($query) {
                $query->whereDoesntHave('subscriptions')->orWhereHas('subscriptions', function ($q) {
                    $q->where('end_date', '<', now()->startOfDay());
                });
            })->count(),
        ];

        // Name sorting
        $users = $query->orderBy('name', 'asc')->get();
        // Return the view with users data
        return view('admin.users.index', compact('users', 'stats'));

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
            $lastSub = $user->subscriptions()->latest()->first();

            $user->subscriptions()->updateOrCreate(
                ['user_id' => $user->id], // Condition to find existing subscription
                [
                    'end_date' => $validated['end_date'],
                    'start_date' => $lastSub->start_date ?? now(),
                    'type' => 'monthly',
                    'price' => $lastSub->price ?? 0,
                ]
            );
        }
        // If end_date is empty, delete subscription
        else {
            $user->subscriptions()->delete();
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
