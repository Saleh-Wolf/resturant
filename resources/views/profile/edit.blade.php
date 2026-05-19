@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4">Profile</h1>

    <div class="card">
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
