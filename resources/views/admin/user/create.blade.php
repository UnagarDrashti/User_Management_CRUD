@extends('admin.layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <label></label>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> Create User</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name_id" class="form-control @error('name') is-invalid @enderror">
                            <span id="name-error-message" style="color: red"></span>
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email_id" class="form-control @error('email') is-invalid @enderror" >
                            <span id="email-error-message" style="color: red"></span>
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="number" pattern="[1-9]{1}[0-9]{9}" name="phone" id="phone_id" class="form-control @error('phone') is-invalid @enderror">
                            <span id="phone-error-message" style="color: red"></span>
                            @error('phone')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select name="gender" id="gender_id" class="form-control @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <span id="gender-error-message" style="color: red"></span>
                            @error('gender')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        
                        </div>
                        <div class="form-group">
                            <label for="profile_img">Profile Image:</label>
                            <input type="file" name="profile_img" id="profile_img_id" class="form-control @error('profile_img') is-invalid @enderror">
                            <span id="profile_img-error-message" style="color: red"></span>
                            @error('profile_img')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function validateForm() {
        var name = document.getElementById('name_id').value;
        var email = document.getElementById('email_id').value;
        var phone = document.getElementById('phone_id').value;
        var gender = document.getElementById('gender_id').value;
        var profile_img = document.getElementById('profile_img_id').value;

        var nameerrorMessage = document.getElementById('name-error-message');
        var emailerrorMessage = document.getElementById('email-error-message');
        var phoneerrorMessage = document.getElementById('phone-error-message');
        var gendererrorMessage = document.getElementById('gender-error-message');
        var profileimgerrorMessage = document.getElementById('profile_img-error-message');

        // Clear previous error messages
        nameerrorMessage.innerHTML = '';
        emailerrorMessage.innerHTML = '';
        phoneerrorMessage.innerHTML = '';
        gendererrorMessage.innerHTML = '';
        profileimgerrorMessage.innerHTML = '';

        var isValid = true;

        if (name === '') {
            nameerrorMessage.innerHTML += 'Please enter a name.<br>';
            isValid = false;
        }

        if (email === '') {
            emailerrorMessage.innerHTML += 'Please enter an email.<br>';
            isValid = false;
        }

        if (phone === '') {
            phoneerrorMessage.innerHTML += 'Please enter a phone number.<br>';
            isValid = false;
        }

        if (gender === '') {
            gendererrorMessage.innerHTML += 'Please select a gender.<br>';
            isValid = false;
        }

        if (profile_img === '') {
            profileimgerrorMessage.innerHTML += 'Please select your profile image.<br>';
            isValid = false;
        }

        return isValid;
    }
</script>

@endsection

