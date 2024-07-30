@if(Auth::user()->roles()->first()->name == 'organizer')
    <a class="btn btn-warning btn-sm edit-url" href="{{ route('events.attendees',UrlHelper::encrypt($row->id)) }}">Attendee</a>    
    <a class="btn btn-warning btn-sm edit-url" href="{{ route('events.detail',UrlHelper::encrypt($row->id)) }}">Edit</a>

    <button class="btn btn-danger btn-sm delete-url" data-id="{{ UrlHelper::encrypt($row->id) }}">Delete</button>
@else
    <a class="btn btn-warning btn-sm edit-url" href="{{ route('events.detail',UrlHelper::encrypt($row->id)) }}">Details</a>
@endif
