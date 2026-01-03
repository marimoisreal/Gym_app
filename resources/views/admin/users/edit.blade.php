@extends('layouts.app')

@section('title', 'Edit Member')

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
        font-size: 1.3rem; 
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
    
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        padding: 10px;
        border-radius: 4px;
    }
    .form-control:focus { 
        border-color: #66c0f4; 
        box-shadow: 0 0 0 0.2rem rgba(102, 192, 244, 0.25); 
    }
    
    .date-btn { 
        border: 1px solid #dee2e6; 
        color: #333; 
        font-weight: 600; 
        font-size: 0.8rem; 
        transition: 0.2s; 
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
            EDIT MEMBER
            <div class="text-white opacity-50">
                {{ $user->email }}
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container shadow-lg">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">System Role</label>
                        <select name="role_id" class="form-select">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Subscription End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control mb-2" 
                               value="{{ $user->subscription ? $user->subscription->end_date->format('Y-m-d') : '' }}">
                        
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-sm date-btn" onclick="addTime(1)">+1 Day</button>
                            <button type="button" class="btn btn-sm date-btn" onclick="addTime(7)">+1 Week</button>
                            <button type="button" class="btn btn-sm date-btn" onclick="addTime(30)">+1 Month</button>
                            <button type="button" class="btn btn-sm date-btn" onclick="addTime(365)">+1 Year</button>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="resetDate()">Reset Date</button>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-steam-green">
                            SAVE CHANGES
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">CANCEL</a>
                    </div>
                </form>

                <div class="mt-5 pt-4 border-top">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete User Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addTime(days) {
        let input = document.getElementById('end_date');
        let currentDate = new Date();
        if (input.value) {
            let selectedDate = new Date(input.value);
            if (selectedDate > currentDate) currentDate = selectedDate;
        }
        currentDate.setDate(currentDate.getDate() + days); // Add days
        input.value = currentDate.toISOString().split('T')[0]; // Splits to get YYYY-MM-DD
    }
    function resetDate() { document.getElementById('end_date').value = ''; }
</script>
@endsection