@extends('layouts.app')

@section('title', 'Event Ticking System')

@section('content')
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <h2>Attendees for : {{ $event->title }}</h2>
            <table id="attendee-table" class="table">
                <thead>
                    <tr>
                        <th>Attendee Name</th>
                        <th>Email</th>
                        <th>Ticket Type</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <a class="btn btn-secondary" href="{{ route('events.index') }}">Back to Event</a>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function(){
        var table = $('#attendee-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('events.attendees',UrlHelper::encrypt($event->id)) }}',
            columns: [
                { data: 'attendee_name', name: 'attendee_name', orderable: false, searchable: false  },
                { data: 'attendee_email', name: 'attendee_email' , orderable: false, searchable: false },
                { data: 'ticket_type', name: 'ticket_type' , orderable: false, searchable: false },
                { data: 'quantity', name: 'quantity' }
            ]
        });

    });
</script>
@endsection