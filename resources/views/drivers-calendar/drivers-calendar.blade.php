@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Driver Schedule Calendar
    </h2>
</div>
<div class="grid grid-cols-12 gap-5 mt-5">
    <!-- BEGIN: Calendar Side Menu -->
    <div class="col-span-12 xl:col-span-4 2xl:col-span-3">
        <div class="box p-5 intro-y">
            <button class="btn btn-primary w-full mt-2" data-tw-toggle="modal" data-tw-target="#add-schedule-modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit-3" class="lucide lucide-edit-3 w-4 h-4 mr-2" data-lucide="edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg> 
                Driver Schedule Calendar
            </button>
            <div class="border-t border-b border-slate-200/60 dark:border-darkmode-400 mt-6 mb-5 py-3" id="drivers-calendar-events">
                @forelse($driverSchedules as $schedule)
                <div class="relative">
                    <div class="event p-3 -mx-3 cursor-pointer transition duration-300 ease-in-out hover:bg-slate-100 dark:hover:bg-darkmode-400 rounded-md flex items-center" 
                        data-event-id="{{ $schedule->id }}" 
                        onclick="showEventDetails({{ $schedule->id }})">
                        <div class="w-2 h-2 rounded-full mr-3" style="background-color: {{ $schedule->status === 'approved' ? '#10b981' : '#f59e0b' }}"></div>
                        <div class="pr-10">
                            <div class="event__title truncate">
                                @if($schedule->driver_user_id && $schedule->driver_user_id !== '')
                                    @php
                                        $driverUser = \App\Models\User::find($schedule->driver_user_id);
                                        $driverName = $driverUser ? $driverUser->name : 'Unknown Driver';
                                    @endphp
                                    {{ $driverName }}
                                @elseif($schedule->driver && $schedule->driver !== '')
                                    {{ $schedule->driver }}
                                @else
                                    Unknown Driver
                                @endif
                            </div>
                            <div class="text-slate-500 text-xs mt-0.5">
                                <span class="event__days">{{ \Carbon\Carbon::parse($schedule->start_datetime)->diffInDays($schedule->end_datetime) + 1 }}</span> Days 
                                <span class="mx-1">•</span> 
                                {{ \Carbon\Carbon::parse($schedule->start_datetime)->format('h:i A') }}
                        </div>
                    </div>
                </div>
                    <!-- <a class="flex items-center absolute top-0 bottom-0 my-auto right-0" href="javascript:;" onclick="editSchedule({{ $schedule->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit w-4 h-4 text-slate-500"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="m18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a> -->
                        </div>
                @empty
                <div class="text-slate-500 p-3 text-center" id="drivers-calendar-no-events">No driver schedules found</div>
                @endforelse
                        </div>
                    </div>
    </div>
    <!-- END: Calendar Side Menu -->
    
    <!-- BEGIN: Calendar Content -->
    <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
        <div class="box p-5">
            <div class="full-calendar fc fc-media-screen fc-direction-ltr fc-theme-standard" id="drivers-calendar"></div>
        </div>
    </div>
    <!-- END: Calendar Content -->
</div>

<!-- BEGIN: Event Details Modal -->
<div id="event-details-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Schedule Details</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label class="form-label font-medium">Driver</label>
                    <div class="form-control-plaintext" id="event-driver-name"></div>
                </div>
                <div class="col-span-12">
                    <label class="form-label font-medium">Vehicle</label>
                    <div class="form-control-plaintext" id="event-vehicle-name"></div>
                </div>
                <div class="col-span-12">
                    <label class="form-label font-medium">Requester</label>
                    <div class="form-control-plaintext" id="event-requester-name"></div>
                </div>
                <div class="col-span-12">
                    <label class="form-label font-medium">Destination</label>
                    <div class="form-control-plaintext" id="event-destination"></div>
                </div>
                <div class="col-span-12">
                    <label class="form-label font-medium">Reason</label>
                    <div class="form-control-plaintext" id="event-reason"></div>
                </div>
                <div class="col-span-6">
                    <label class="form-label font-medium">Start Date & Time</label>
                    <div class="form-control-plaintext" id="event-start-datetime"></div>
                </div>
                <div class="col-span-6">
                    <label class="form-label font-medium">End Date & Time</label>
                    <div class="form-control-plaintext" id="event-end-datetime"></div>
                </div>
                <div class="col-span-12">
                    <label class="form-label font-medium">Status</label>
                    <div class="form-control-plaintext" id="event-status"></div>
                </div>
                <div class="col-span-12" id="event-remarks-container" style="display: none;">
                    <label class="form-label font-medium">Remarks</label>
                    <div class="form-control-plaintext" id="event-remarks"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Close</button>
                <!-- <button type="button" class="btn btn-primary w-20" onclick="editScheduleFromModal()">Edit</button> -->
            </div>
        </div>
    </div>
</div>
<!-- END: Event Details Modal -->

@endsection

@push('styles')
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/fullcalendar/main.min.css') }}">
    
    <!-- Custom Calendar Styling -->
    <style>
        /* Custom styling for calendar events */
        #drivers-calendar .fc-event {
            border: 2px solid #3b82f6 !important; /* Blue border */
            border-radius: 4px !important;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2) !important;
            margin: 1px !important;
        }
        
        #drivers-calendar .fc-event-main {
            padding: 2px 4px !important;
        }
        
        #drivers-calendar .fc-event-title {
            font-weight: 600 !important;
            color: #1e40af !important; /* Darker blue text */
        }
        
        /* Hover effect for events */
        #drivers-calendar .fc-event:hover {
            border-color: #1d4ed8 !important; /* Darker blue on hover */
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3) !important;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        
        /* Ensure events are properly spaced */
        #drivers-calendar .fc-daygrid-event-harness {
            margin: 1px 0 !important;
        }
        
        /* Custom colors for different statuses */
        #drivers-calendar .fc-event.status-approved {
            border-color: #10b981 !important; /* Green for approved */
            background-color: rgba(16, 185, 129, 0.1) !important;
        }
        
        #drivers-calendar .fc-event.status-pending {
            border-color: #f59e0b !important; /* Yellow for pending */
            background-color: rgba(245, 158, 11, 0.1) !important;
        }
        
        #drivers-calendar .fc-event.status-rejected {
            border-color: #ef4444 !important; /* Red for rejected */
            background-color: rgba(239, 68, 68, 0.1) !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- FullCalendar JavaScript -->
    <script src="{{ asset('assets/plugin/fullcalendar/main.min.js') }}"></script>
    
    <script>
        // Pass PHP data to JavaScript
        window.calendarEvents = @json($calendarEvents);
    </script>
    <script src="{{ asset('js/drivers-calendar/drivers-calendar.js') }}"></script>
@endpush