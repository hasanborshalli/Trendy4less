@extends('layouts.app')

@section('content')
<div class="t4l-section t4l-fade-up" style="max-width: 420px; margin: 2.5rem auto;">
    <h1 class="t4l-section-title" style="font-size: 1.6rem;">Admin Login</h1>

    @if($errors->any())
    <div class="t4l-alert" style="background:#fee2e2; border-color:#fecaca; color:#b91c1c;">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="t4l-admin-login-form">
        @csrf

        <div class="t4l-form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="t4l-form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Login
        </button>
    </form>
</div>
@endsection