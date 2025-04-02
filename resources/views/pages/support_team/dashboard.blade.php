@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @if(Qs::userIsTeamSA())
       <div class="row">
           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-blue-400 has-bg-image">
                   <div class="media">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'student')->count() }}</h3>
                           <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users4 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-danger-400 has-bg-image">
                   <div class="media">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'teacher')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Teachers</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users2 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <!-- <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-success-400 has-bg-image">
                   <div class="media">
                       <div class="mr-3 align-self-center">
                           <i class="icon-pointer icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'admin')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Administrators</span>
                       </div>
                   </div>
               </div>
           </div> -->

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-indigo-400 has-bg-image">
                   <div class="media">
                       <div class="mr-3 align-self-center">
                           <i class="icon-user icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'parent')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Parents</span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       @endif

    {{--Events Calendar Begins--}}
     {{-- <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">School Events Calendar</h5>
         {!! Qs::getPanelOptions() !!}
        </div>

          <div class="card-body">
            <div class="fullcalendar-basic"></div>
        </div>  
    </div>  --}}
    {{--Events Calendar Ends--}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">School Events Calendar</h5>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <div id="calendar" class="calendar-container"></div>
        </div>
    </div>
    
    <!-- Icon for Event Entry -->
    <!-- Icon for Event Entry -->
 <button type="button" class="btn btn-primary event-entry-icon">
    <i class="mdi mdi-export"></i> Add Event
</button>
    
    <!-- Event Entry Form -->
    <div class="event-entry-form" style="display: none;">
        <form method="POST" class="d-block ajaxForm" action="{{ route('events.store') }}">
            @csrf
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group col-md-12">
                <label for="start_date">Event Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
              </div>
              
              <div class="form-group col-md-12">
                <label for="end_date">Event End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
              </div>
            <!-- Add more form fields for event details if needed -->
            <button type="submit" class="btn btn-primary">Save Event</button>
        </form>
    </div>  
    <div id="calendar"></div>

<div id="eventDetails">
  <h3>Event Details</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Start Date</th>
        <th>End Date</th>
      </tr>
    </thead>
    <tbody id="eventDetailsBody">
    </tbody>
  </table>
</div>

      
  <script>
    $(document).ready(function() {
      // Initialize the calendar
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        events: '/events', // URL to fetch events from the server
        // Additional options and callbacks
      // Event click callback
    eventClick: function(event) {
      // Display event details in the table
      var $eventDetailsBody = $('#eventDetailsBody');
      $eventDetailsBody.empty(); // Clear existing event details

      // Append a new row with event details
      var $row = $('<tr></tr>');
      $row.append('<td>' + event.title + '</td>');
      $row.append('<td>' + event.start.format('YYYY-MM-DD HH:mm') + '</td>');
      $row.append('<td>' + event.end.format('YYYY-MM-DD HH:mm') + '</td>');
      $eventDetailsBody.append($row);

      // Scroll to the event details section
      $('html, body').animate({
        scrollTop: $('#eventDetails').offset().top
      }, 500);
    }
      });
    });
  
          $(document).ready(function() {
            // Initialize FullCalendar
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: "{{ route('events.index') }}",
                editable: false,
                eventClick: function(event) {
                    // Handle event click if needed
                }
            });
    
            // Show event entry form on icon click
            $('.event-entry-icon').click(function() {
                $('.event-entry-form').show();
            });
    
            // Submit event entry form using AJAX
            $('#eventForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
    
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        form[0].reset();
                        $('.event-entry-form').hide();
                        // Refresh the calendar to display the new event
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                });
            });
        });
       
    
    </script>
    
    
    @endsection
