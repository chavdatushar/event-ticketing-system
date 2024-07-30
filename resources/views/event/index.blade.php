@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Event Dashboard</h1>
        @if(Auth::user()->roles()->first()->name != 'attendee')
            <a class="btn btn-primary" href="{{ route('events.create') }}">Create</a>
            
        @endif
        <a href="{{ route('events.export') }}" class="btn btn-success mb-3">Export Events to Excel</a>
        <table id="events-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function(){
        var table = $('#events-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('events.index') }}',
            columns: [
                { data: 'title', name: 'title' },
                { data: 'date', name: 'date' },
                { data: 'location', name: 'location' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

        $('#events-table').on('click', '.delete-url', function() {
            const id = $(this).data('id');
            $.ajax({
                url: '{{ route('events.destroy', '') }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

    });
</script>
@endsection
