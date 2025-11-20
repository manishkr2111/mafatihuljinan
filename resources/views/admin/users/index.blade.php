@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="min-h-screen bg-gray-100 p-2 sm:p-4">
    <div class="w-full max-w-7xl mx-auto bg-white rounded-xl shadow-lg">

        <!-- Header Title -->
        <div class="p-4 border-b border-gray-200">
            <h1 class="text-lg sm:text-xl font-bold text-[#034E7A]">Users Management</h1>
        </div>

        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 flex flex-col space-y-3 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
            <!-- Search Input -->
            <input
                type="text"
                id="userSearch"
                placeholder="Search by Name or Email..."
                class="w-full sm:flex-1 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">

            <!-- Role Filter -->
            <select id="roleFilter" class="w-full sm:w-auto px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="subscriber">Subscriber</option>
            </select>
        </div>

        <!-- Horizontal Scroll Container -->
        <div class="overflow-x-auto">
            <div class="min-w-max">
                <!-- Table Header -->
                <div class="grid grid-cols-12 bg-[#034E7A] text-white sticky top-0 z-10">
                    <div class="col-span-1 px-2 sm:px-3 py-2 sm:py-3 font-semibold text-xs sm:text-sm">#</div>
                    <div class="col-span-2 px-2 sm:px-3 py-2 sm:py-3 font-medium text-gray-800 max-w-[120px] truncate">Name</div>
                    <div class="col-span-3 px-2 sm:px-3 py-2 sm:py-3 text-gray-700 text-xs sm:text-sm max-w-[180px] break-all truncate">Email</div>
                    <div class="col-span-1 px-2 sm:px-3 py-2 sm:py-3 font-semibold text-xs sm:text-sm">Role</div>
                    <div class="col-span-1 px-2 sm:px-3 py-2 sm:py-3 font-semibold text-xs sm:text-sm">Verified</div>
                    <div class="col-span-2 px-2 sm:px-3 py-2 sm:py-3 font-semibold text-xs sm:text-sm">Created At</div>
                    <div class="col-span-2 px-2 sm:px-3 py-2 sm:py-3 font-semibold text-center text-xs sm:text-sm">Actions</div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-gray-200">
                    @forelse($users as $index => $user)
                    <div class="grid grid-cols-12 hover:bg-gray-50 items-center user-row">
                        <div class="col-span-1 px-2 sm:px-3 py-2 sm:py-3 text-xs sm:text-sm">{{ $index + 1 }}</div>
                        <div class="col-span-2 px-2 sm:px-3 py-2 sm:py-3 font-medium text-gray-800 max-w-[120px] truncate">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="hover:underline text-blue-600 text-xs sm:text-sm">
                                {{ $user->name }}
                            </a>
                        </div>
                        <div class="col-span-3 px-2 sm:px-3 py-2 sm:py-3 text-gray-700 text-xs sm:text-sm max-w-[180px] truncate break-all">
                            {{ $user->email }}
                        </div>
                        <div class="col-span-1 px-2 sm:px-3 py-2 sm:py-3 text-gray-700 text-xs sm:text-sm">
                            {{ $user->role }}
                        </div>
                        <div class="col-span-1 px-2 sm:px-3 py-2 sm:py-3">
                            @if($user->email_verified_at)
                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full whitespace-nowrap">Yes</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full whitespace-nowrap">No</span>
                            @endif
                        </div>
                        <div class="col-span-2 px-2 sm:px-3 py-2 sm:py-3 text-gray-600 text-xs sm:text-sm whitespace-nowrap">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</div>
                        <div class="col-span-2 px-2 sm:px-3 py-2 sm:py-3 flex items-center justify-center space-x-1 sm:space-x-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 px-2 sm:px-3 py-1 rounded bg-blue-50 hover:bg-blue-100 text-xs sm:text-sm transition whitespace-nowrap">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 px-2 sm:px-3 py-1 rounded bg-red-50 hover:bg-red-100 text-xs sm:text-sm transition whitespace-nowrap">Delete</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        No users found.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- JS Frontend Filter -->
<script>
    const searchInput = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');

    function filterUsers() {
        const filterText = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value.toLowerCase();

        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const role = row.children[3].textContent.toLowerCase();

            const matchesSearch = name.includes(filterText) || email.includes(filterText);
            const matchesRole = selectedRole === "" || role === selectedRole;

            row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
</script>
@endsection