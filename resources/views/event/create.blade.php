@extends('layouts.app')

@section('title', 'Event Ticking System')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Create Events</h1>
        <div class="form-row align-items-center">

            {!! Form::open(['url' => '#','id' => 'event-form',"class"=>"mb-3"]) !!}
            
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        {!! Form::text('title', '',['class'=>"form-control","placeholder"=>"Enter title","id"=>"title","required"=>true]); !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        {!! Form::textarea('description', '',['class'=>"form-control","placeholder"=>"Enter description","id"=>"description","required"=>true]); !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                        {!! Form::date('date', '',['class'=>"form-control","placeholder"=>"Select date","id"=>"date","required"=>true]); !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        {!! Form::text('location', '',['class'=>"form-control","placeholder"=>"Select location","id"=>"location","required"=>true]); !!}
                    </div>
                </div>
                <br>
                @foreach ($ticketTypes as $ticketType)
                    <div class="form-group row">
                        <label for="{{$ticketType->value}}" class="col-sm-2 col-form-label">Number of {{$ticketType->label()}} Tickets : </label>
                        <div class="col-sm-5">
                            {!! Form::number("tickets[".$ticketType->value."]" , '',['class'=>"form-control","placeholder"=>"Enter Ticket No. Avaibility","id"=>"{{$ticketType->value}}","required"=>true]); !!}
                        </div>
                        <div class="col-sm-5">
                            {!! Form::number("ticket_price[".$ticketType->value."]" , '',['class'=>"form-control","placeholder"=>"Enter Ticket Price","id"=>"price_{{$ticketType->value}}","required"=>true]); !!}
                        </div>
                    </div>    
                @endforeach
                <div class="form-group">
                    
                    <label for="submit" class="col-sm-2 col-form-label"></label>
                    {!! Form::button('Submit',['type'=>"submit",'class'=>'btn btn-primary']) !!}
                    <a class="btn btn-secondary" href="{{ route('events.index') }}">Cancel</a>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#event-form').validate({
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    location: {
                        required: true,
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a Title",
                    },
                    description: {
                        required: "Please enter a Description",
                    },
                    date: {
                        required: "Please select a date",
                    },
                    location: {
                        required: "Please enter location",
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: '{{ route('events.store') }}',
                        method: 'POST',
                        data: $(form).serialize(),
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
                }
            });
        });
</script>
@endsection()