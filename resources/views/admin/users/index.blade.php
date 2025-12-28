@extends('layouts.app')

@section('title', 'All Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gym Members</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
    </div>

    <table class="table table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-info text-dark">{{ $user->role->name ?? 'No Role' }}</span></td>
                    <td><code>{{ $user->slug }}</code></td> {{-- Slug replaces an unclear id with a readable name --}}
                    <td><a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection