@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role_id" id="role_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label text-black">Subscription End Date</label>
                            <input type="date" name="end_date" id="end_date" 
                                class="form-control" 
                                value="{{ $user->subscription ? $user->subscription->end_date->format('Y-m-d') : '' }}">
                            
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-light text-black" onclick="addTime(1)">+1 Day</button>
                                <button type="button" class="btn btn-sm btn-outline-light text-black" onclick="addTime(7)">+1 Week</button>
                                <button type="button" class="btn btn-sm btn-outline-light text-black" onclick="addTime(30)">+1 Month</button>
                                <button type="button" class="btn btn-sm btn-outline-light text-black" onclick="addTime(365)">+1 Year</button>

                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetDate()">Reset Date</button>
                            </div>
                        </div>

                        <script>
                            function addTime(days) 
                            {
                                let input = document.getElementById('end_date');
                                let currentDate = new Date();

                                // If in the input there's a date and it's in the future,
                                // If the date is not present or it's in the past — extend from "today"
                                if (input.value) {
                                    let selectedDate = new Date(input.value);
                                    if (selectedDate > currentDate) {
                                        currentDate = selectedDate;
                                    }
                                }

                                currentDate.setDate(currentDate.getDate() + days);
                                input.value = currentDate.toISOString().split('T')[0];
                            }

                            function resetDate() {
                                document.getElementById('end_date').value = '';
                            }
                        </script>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                Save Changes
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </form>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="mt-3"
                        onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection