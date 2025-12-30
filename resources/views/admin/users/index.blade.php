@extends('layouts.app')

@section('title', 'All Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gym Members</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
    </div>

    <table class="table table-hover shadow-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Slug</th>
                <th>Subscription</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="fw-bold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge rounded-pill bg-info text-dark">{{ $user->role->name ?? 'No Role' }}</span>
                    </td>
                    <td><code>{{ $user->slug }}</code></td>
                    <td>
                        @if($user->subscription)
                            <span
                                class="fw-medium {{ $user->subscription->end_date->isToday() || $user->subscription->end_date->isFuture() ? 'text-success' : 'text-danger' }}">
                                ● {{ $user->subscription->end_date->format('d.m.Y') }}
                            </span>
                        @else
                            <span class="text-muted small italic">No subscription</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                            class="btn btn-sm btn-outline-primary px-3">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection