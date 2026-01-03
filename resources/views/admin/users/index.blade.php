@extends('layouts.app')

@section('title', 'All Users')

@section('content')
<style>
    body {
        background-color: #171a21; 
        color: #ffffff;
    }

    .admin-header {
        background-color: #1b2838;
        padding: 20px 0;
        border-bottom: 1px solid #2a475e;
    }

    .brand-link { 
        color: #66c0f4; 
        text-decoration: none; 
        font-size: 1.5rem; 
        font-weight: bold; 
        transition: color 0.3s ease; 
        cursor: default;
    }

    .brand-link:hover { 
        color: #ffffff; 
        text-shadow: 0 0 8px rgba(102, 192, 244, 0.4); 
    }

    /* stats - wtie text */
    .stat-item { 
        text-align: center; 
    }

    .stat-label { 
        color: #ffffff; font-size: 0.8rem;
        letter-spacing: 1px; 
    }
    .stat-count { 
        color: #66c0f4; 
        font-size: 1.8rem; 
        font-weight: bold; 
        display: block; 
    }

    /* serching bar */
    .search-container { width: 100%; 
        margin-bottom: 20px; 
    }

    .search-input {
        background-color: #2a475e !important;
        border: 1px solid #3d6486 !important;
        color: white !important;
        border-radius: 4px 0 0 4px;
    }
    .search-input::placeholder { 
        color: #859bb0; 
    }

    /* table - style and background color*/
    .table-container {
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        color: #000000; 
    }

    .table-custom { 
        margin-bottom: 0; 
    }

    .table-custom thead { 
        background-color: #1b2838; 
        color: #66c0f4; 
    }

    .table-custom tbody td { 
        color: #000000; 
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .user-name { 
        color: #000000; 
        font-weight: 700; 
    }

    /* button like in steam */
    .btn-steam-green {
        background-color: #4c6b22;
        color: white;
        border: none;
        padding: 8px 20px;
    }
    .btn-steam-green:hover { 
        background-color: #5c832a; 
        color: white; 
    }
</style>

<div class="admin-header mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <div class="brand-link">
                    GYM ADMIN <i class="bi bi-shield-lock small opacity-50"></i>
                </div>
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-center gap-5">
                    <div class="stat-item">
                        <span class="stat-label">TOTAL</span>
                        <span class="stat-count">{{ $stats['total'] }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">ACTIVE</span>
                        <span class="stat-count text-success">{{ $stats['active'] }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">EXPIRED</span>
                        <span class="stat-count text-danger">{{ $stats['expired'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 text-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-steam-green rounded shadow-sm">
                    + Add New Member
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.users.index') }}" method="GET" class="search-container">
                <div class="input-group input-group-lg shadow">
                    <input type="text" name="search" class="form-control search-input" 
                           placeholder="Search members..." value="{{ request('search') }}">
                    <button class="btn btn-primary px-5" type="submit">Search</button>
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary d-flex align-items-center">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="table-container shadow-lg">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Role</th>
                    <th class="py-3"><a class="text-decoration-none text-black" href="{{ route('admin.users.index', ['sort' => 'subscription', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                    Subscription <span class="ms-1 text-decoration-none text-black">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span></a>
                </th>
                    <th class="py-3 text-center px-4" style="width: 15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="px-4 user-name">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-dark">{{ strtoupper($user->role->name ?? 'User') }}</span>
                        </td>
                        <td>
                            @if(in_array($user->role->name, ['admin', 'trainer']))
                                <span class="text-primary fw-bold">∞ Unlimited</span>
                            @elseif($user->subscription)
                                <span class="{{ $user->subscription->end_date->isFuture() ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $user->subscription->end_date->format('d.m.Y') }}
                                </span>
                            @else
                                <span class="text-muted">Not Active</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="btn btn-sm btn-outline-dark px-3 w-100">Manage<span class="material-symbols-outlined">personedit</span></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection