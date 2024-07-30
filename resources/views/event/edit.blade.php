@extends('layouts.app')

@section('title', 'Event Ticking System')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Edit Events</h1>
        <div class="form-row align-items-center">

            {!! Form::open(['url' => '#','id' => 'event-form',"class"=>"mb-3"]) !!}
            
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        {!! Form::text('title', $event->title,['class'=>"form-control","placeholder"=>"Enter title","id"=>"title","required"=>true]); !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        {!! Form::textarea('description', $event->description,['class'=>"form-control","placeholder"=>"Enter description","id"=>"description","required"=>true]); !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                        {!! Form::date('date', $event->date,['class'=>"form-control","placeholder"=>"Select date","id"=>"date","required"=>true]); !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        {!! Form::text('location', $event->location,['class'=>"form-control","placeholder"=>"Select location","id"=>"location","required"=>true]); !!}
                    </div>
                </div>
                @foreach ($event->tickets as $ticket)
                    <div class="form-group row">
                        <label for="{{$ticket->type}}" class="col-sm-2 col-form-label">Number of {{$ticket->type}} Tickets : </label>
                        <div class="col-sm-5">
                            {!! Form::number("tickets[".$ticket->type->value."]" , $ticket->availability,['class'=>"form-control","placeholder"=>"Enter Ticket No. Avaibility","id"=>$ticket->type->value,"required"=>true]); !!}
                        </div>
                        <div class="col-sm-5">
                            {!! Form::number("ticket_price[".$ticket->type->value."]" , $ticket->price,['class'=>"form-control","placeholder"=>"Enter Ticket Price","id"=>"price_".$ticket->type->value,"required"=>true]); !!}
                        </div>
                        {!! Form::hidden("ticket_id[".$ticket->type->value."]" , $ticket->id,["required"=>true]); !!}
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
                        url: '{{ route('events.update',UrlHelper::encrypt($event->id)) }}',
                        method: 'PUT',
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