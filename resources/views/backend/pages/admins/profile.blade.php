@extends('backend.layouts.master')

@section('title')
Profile - Admin Panel
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Profile</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="#">Profile</a></li>
                    <li><span>Edit Profile</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->
<div class="container">
    <div>
        @if(Auth::guard('admin')->user()->role == 'alc')    
            <h6 class="mt-4" style="float:right; color:blue; font-weight:650"> Assign Under: {{ Auth::guard('admin')->user()->assignUnder->name}} </h6>
        @endif
        <h1>Edit Profile</h1>
    </div>
    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $admin->name) }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
        </div>

        <!-- Username -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $admin->username) }}" required  >
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">New Password (leave blank if not changing)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection