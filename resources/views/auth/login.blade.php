@extends('layouts.app')
@section('title','Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Login
            </div>
            <div class="card-body">
                {!! Form::open(['url' => '#','id' => 'login-form']) !!}
                    <div class="md-3">
                        <label class="form-label">Email</label>
                        {!! Form::email('email', "", ["class"=>"form-control"]) !!}
                    </div>
                    <div class="md-3">
                        <label class="form-label">Password</label>
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                    
                    {!! Form::button('Login',['type'=>"submit",'class'=>'btn btn-primary my-3']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('#login-form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route('do-login') }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route('events.index') }}';
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
</script>
@endsection