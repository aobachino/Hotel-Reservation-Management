@extends('template.master')
@section('title', 'Add Customer')
@section('content')
<div class="row justify-content-md-center">
    <div class="col-lg-8">
        @if (session('success'))
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        </div>
        @endif
        <div class="card shadow-sm border">
            <div class="card-header">
                <h2>Add Customer</h2>
            </div>
            <div class="card-body p-3">
                <form class="row g-3" method="POST" action="{{ route('customer.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Password fields commented out for registration, uncomment as needed -->
                    <div class="col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" required>
                        @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror   
                    </div>

                    <div class="col-md-12">
                        <label for="birthdate" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                            id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                        @error('birthdate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                            aria-label="Gender" required>
                            <option value="" selected disabled>Select Gender</option>
                            <option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
                            <option value="Female" @if(old('gender') == 'Female') selected @endif>Female</option>
                        </select>
                        @error('gender')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="job" class="form-label">Job</label>
                        <input type="text" class="form-control @error('job') is-invalid @enderror" id="job" name="job"
                            value="{{ old('job') }}" required>
                        @error('job')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="avatar" class="form-label">Profile Picture</label>
                        <input class="form-control @error('avatar') is-invalid @enderror" type="file" name="avatar"
                            id="avatar">
                        @error('avatar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
