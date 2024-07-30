@extends('layouts.app')
@section('title','Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Register
            </div>
            <div class="card-body">
                {!! Form::open(['url' => '#','id' => 'register-form']) !!}
                    <div class="md-3">
                        <label class="form-label">Role</label>
                        {!! Form::select('role_id', $roleArr,"", ["class"=>"form-control"]) !!}
                    </div>
                
                    <div class="md-3">
                        <label class="form-label">Name</label>
                        {!! Form::text('name', "", ["class"=>"form-control"]) !!}
                    </div>
                    <div class="md-3">
                        <label class="form-label">Email</label>
                        {!! Form::email('email', "", ["class"=>"form-control"]) !!}
                    </div>
                    <div class="md-3">
                        <label class="form-label">Password</label>
                        {!! Form::password('password', ['class' => 'form-control','id'=>"password"]) !!}
                    </div>
                    <div class="md-3">
                        <label class="form-label">Confirm Password</label>
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::button('Register',['type'=>"submit",'class'=>'btn btn-primary my-3']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('#register-form').validate({
            rules: {
                role_id: {
                    required: true
                },
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    equalTo: '#password'
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    minlength: "Your name must be at least 3 characters long"
                },
                email: "Please enter a valid email address",
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    minlength: "Your password confirmation must be at least 6 characters long",
                    equalTo: "Password and confirmation do not match"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: '{{ route('do-register') }}',
                    method: 'POST',
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = '{{ route('login') }}';
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            }
        });
    });
    
</script>
@endsection