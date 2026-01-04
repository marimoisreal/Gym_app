@extends('layouts.app')

@section('title', 'New Member')

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

    .form-container { 
        background-color: #ffffff; 
        border-radius: 8px; 
        color: #000000; 
        padding: 30px; 
    }

    .form-label { 
        font-weight: 700; 
        color: #1b2838; 
        text-transform: uppercase; 
        font-size: 0.85rem; 
    }
    .btn-steam-green { 
        background-color: #4c6b22; 
        color: white; 
        border: none; 
        font-weight: bold; 
        padding: 10px 30px;
        border-radius: 4px;
        transition: all 0.3s ease; 
    }

    .btn-steam-green:hover { 
        background-color: #67922d; 
        color: white;
        box-shadow: 0 0 10px rgba(103, 146, 45, 0.4); 
    }

    .btn-steam-green:active {
        background-color: #3e561b; 
        transform: translateY(1px); 
    }
</style>

<div class="admin-header mb-5">
    <div class="container">
        <div class="brand-link">
            ADD NEW MEMBER
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container shadow-lg">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Ivan Ivanov">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="ivan@example.com">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-steam-green px-5 py-2">CREATE MEMBER</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">CANCEL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection