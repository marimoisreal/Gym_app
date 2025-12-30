@extends('layouts.app')

@section('title', 'All Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gym Members</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total</h6>
                    <h3>{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h6 class="text-muted">Active Now</h6>
                    <h3>{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h6 class="text-muted">Expired</h6>
                    <h3>{{ $stats['expired'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name..."
                value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Search</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
    <table class="table table-hover shadow-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Slug</th>
                <th><a href="{{ route('admin.users.index', [
        'sort' => 'subscription',
        'direction' => request('direction') === 'asc' ? 'desc' : 'asc',
        'search' => request('search'),
        'role' => request('role')
    ]) }}" class="text-decoration-none text-white d-block">Subscription{!! request('sort') === 'subscription' ? (request('direction') === 'asc' ? '↑' : '↓') : '' !!}</a>
                </th>
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