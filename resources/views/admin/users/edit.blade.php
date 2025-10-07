@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white rounded-top">
            <h5 class="mb-0">User Details</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('users.editOrUpdate', $user) }}">
                @csrf
                @method('PUT')

                <div style="max-height: 85vh; overflow-y: auto; overflow-x: hidden;">
                    <!-- Profile Image -->
                    <div class="text-center mb-4">
                        <img src="{{ $user->detail?->profile_image ? asset('storage/' . $user->detail->profile_image) : asset('storage/profile_images/default_image.jpg') }}"
                            alt="Profile Image" class="img-fluid rounded-circle border border-2 border-secondary mb-2" style="max-width:150px;">
                    </div>

                    <!-- Main User Info -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Verified</label>
                            <input type="text" class="form-control" value="{{ $user->email_verified_at ? 'Yes' : 'No' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ID Verified</label>
                            <input type="text" class="form-control" value="{{ $user->id_verified ? 'Yes' : 'No' }}" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Created At</label>
                            <input type="text" class="form-control" value="{{ $user->created_at->format('Y-m-d H:i') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Updated At</label>
                            <input type="text" class="form-control" value="{{ $user->updated_at->format('Y-m-d H:i') }}" readonly>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    @if($user->detail)
                    <h5 class="mt-4 mb-3">Profile Details</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" value="{{ $user->detail->user_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control" value="{{ $user->detail->phone }}" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">DOB</label>
                            <input type="text" class="form-control" value="{{ $user->detail->dob }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <input type="text" class="form-control" value="{{ $user->detail->gender }}" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pronouns</label>
                            <input type="text" class="form-control" value="{{ $user->detail->pronouns }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bio</label>
                            <textarea class="form-control" rows="2" readonly>{{ $user->detail->bio }}</textarea>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subscription Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->detail->subscription_status) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subscription Period</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="text" class="form-control" value="{{ $user->detail->subscription_start_date }}" readonly>
                                <span>to</span>
                                <input type="text" class="form-control" value="{{ $user->detail->subscription_end_date }}" readonly>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Editable Status -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Status</button>
                    <a href="{{ route('users') }}" class="btn btn-secondary rounded-pill px-4">Back to Users</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
