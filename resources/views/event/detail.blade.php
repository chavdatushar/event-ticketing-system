@extends('layouts.app')

@section('title', 'Event Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>{{ $title }} </h1>
        <table class="table">
            <tr>
                <td>Title</td>
                <td>{{ $event->title }}</td>
            </tr>
            <tr>
                <td>Description</td>
                <td>{{ $event->description }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td>{{ $event->date }}</td>
            </tr>
            <tr>
                <td>Location</td>
                <td>{{ $event->location }}</td>
            </tr>
        </table>
    </div>      
                
    <div class="col-md-12">           
        <h3>Tickets</h3>
        
            <table class="table table-responsive">
                <tr>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Avaibility</th>
                    <th>Actions</th>
                </tr>
                @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->type }}</td>
                    <td>{{ $ticket->price }}</td>
                    <td>{{ $ticket->availability }} Tickets Left </td>
                    <td>
                        {!! Form::open(['url' => '#','class' => 'purchase-form']) !!}
                            {!! Form::hidden("ticket_type" , $ticket->type,["required"=>true]); !!}
                            {!! Form::hidden("ticket_id" , $ticket->id,["required"=>true]); !!}
                            {!! Form::number("quantity" , 1,['class'=>"","min"=>1,"max"=>$ticket->availability,"required"=>true]); !!}
                            <button type="submit" class="btn btn-primary">Purchase</button>
                        {!! Form::close() !!}
                    </td>
                    
                </tr>
                @endforeach

               
            </table>
            <h2>Comments</h2>
            @foreach($event->comments as $comment)
            <div class="comment">
                <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
            </div>
            @endforeach
            @auth
            <div class="col-md-4">
                <h3>Leave a Comment</h3>
                {!! Form::open(['url' => '#','id' => 'comment-form']) !!}
                    {!! Form::hidden("event_id" , $event->id,["required"=>true]); !!}
                    <div class="form-group">
                        <label for="content">Your Comment</label>
                        {!! Form::textarea('content', '',['class'=>"form-control","rows"=>2,"id"=>"content","required"=>true]); !!}
                    </div>
                    <button type="button" id="submit-comment" class="btn btn-primary">Submit</button>
                {!! Form::close() !!}
                <div id="comment-message" class="mt-2"></div>
            </div>
            @endauth

        <div class="form-group">                    
            {{-- {!! Form::button('Submit',['type'=>"submit",'class'=>'btn btn-primary']) !!} --}}
            <a class="btn btn-secondary" href="{{ route('events.index') }}">Back to Event</a>
        </div>
    </div>
</div>
<!-- Button trigger modal -->
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Payment For Event Tickets</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <div class="modal-body">
            <div id="payment-message" class="alert mt-3" style="display: none;"></div>
             <!-- Stripe Payment Form -->
            <div id="payment-form-container" style="display: none;">
                <h4>Enter Card Details</h4>
                {!! Form::open(['url' => '#','id' => 'payment-form']) !!}
                    <div id="card-element"></div>
                    <button id="submit-button" class="btn btn-success mt-3">Pay</button>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
     $(document).ready(function() {
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        $('.purchase-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            $.ajax({
                url: '{{ route('createpaymentrequest') }}',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    if(response.success) {
                        $('#exampleModal').modal('show')
                        $('#payment-form-container').show();
                        $('#payment-form').data('client-secret', response.data.clientSecret);
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        $('#payment-form').on('submit', function(e) {
            e.preventDefault();
            const clientSecret = $(this).data('client-secret');

            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            }).then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                    showMessage(result.error.message, 'alert-danger');
                } else {
                    alert('Payment successful!');
                    showMessage('Payment successful!', 'alert-success');
                    location.reload(); // reload the page to update ticket availability
                }
            });
        });

        function showMessage(message, alertType) {
            const messageDiv = $('#payment-message');
            messageDiv.text(message).addClass(alertType).show();
        }

        $('#submit-comment').on('click', function() {
            $.ajax({
                url: '{{ route('comments.store') }}',
                method: 'POST',
                data: $('#comment-form').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        location.reload(); // reload the page to update ticket availability
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
</script>
@endsection()