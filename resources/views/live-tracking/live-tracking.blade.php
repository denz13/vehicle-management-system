@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Live Tracking - Driver Dashboard
    </h2>
    <div class="ml-auto">
        <span class="text-slate-500">Driver: <strong>{{ $currentUser->name }}</strong></span>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- BEGIN: Driver Info Card -->
    <div class="intro-y col-span-12 lg:col-span-4 xl:col-span-3">
        <div class="box mt-5">
            <div class="px-4 pb-3 pt-5">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                        <img src="{{ $currentUser->photo_url ?? 'dist/images/profile-4.jpg' }}" alt="{{ $currentUser->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="font-medium text-lg">{{ $currentUser->name }}</div>
                        <div class="text-slate-500">{{ $currentUser->email }}</div>
                        @if($currentUser->position)
                            <div class="text-slate-400 text-sm">{{ $currentUser->position->name }}</div>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-500">Total Trips:</span>
                        <div class="font-medium">{{ $approvedReservations->count() }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500">Status:</span>
                        <div class="text-success font-medium">Active Driver</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Driver Info Card -->

    <!-- BEGIN: Current Trip Card -->
    <div class="intro-y col-span-12 lg:col-span-8 xl:col-span-9">
        <div class="intro-y box lg:mt-5">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Current & Upcoming Trips
                </h2>
                <button class="btn btn-outline-secondary btn-sm" onclick="refreshReservations()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw w-4 h-4 mr-1"><path d="M3 2v6h6"></path><path d="M21 12A9 9 0 006 5.3L3 8"></path><path d="M21 22v-6h-6"></path><path d="M3 12a9 9 0 0015 6.7l3-2.7"></path></svg>
                    Refresh
                </button>
            </div>
            <div class="p-5">
                @if($approvedReservations->count() > 0)
                    <!-- Debug Information -->
                    @if(config('app.debug'))
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h3 class="font-medium text-yellow-800 mb-2">Debug Info:</h3>
                        <div class="text-sm text-yellow-700">
                            <p>Total Reservations in System: {{ $allReservations->count() }}</p>
                            <p>Approved Reservations for Driver: {{ $approvedReservations->count() }}</p>
                            <p>Current User ID: {{ $currentUser->id }}</p>
                            <p>Current User Name: {{ $currentUser->name }}</p>
                            @if($allReservations->count() > 0)
                            <p>Sample Reservations in System:</p>
                            <ul class="list-disc list-inside ml-4">
                                @foreach($allReservations->take(3) as $res)
                                <li>ID: {{ $res->id }}, Driver ID: {{ $res->driver_user_id }}, Status: {{ $res->status }}, From: "{{ $res->requested_name }}", To: "{{ $res->destination }}"</li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-4" id="reservations-container">
                        @foreach($approvedReservations as $reservation)
                        <div class="border rounded-lg p-4 hover:bg-slate-50 dark:hover:bg-darkmode-400 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 bg-primary/10 text-primary flex items-center justify-center rounded-full mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16,8 20,8 23,11 23,16 16,16 16,8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $reservation->vehicle->plate_number ?? 'N/A' }}</div>
                                            <div class="text-slate-500 text-sm">{{ $reservation->vehicle->vehicle_type ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-slate-500">From:</span>
                                            <div class="font-medium">{{ $reservation->requested_name ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <span class="text-slate-500">To:</span>
                                            <div class="font-medium">{{ $reservation->destination ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-slate-500">Date:</span>
                                            <div class="font-medium">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('M d, Y') }}</div>
                                        </div>
                                        <div>
                                            <span class="text-slate-500">Time:</span>
                                            <div class="font-medium">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_datetime)->format('h:i A') }}</div>
                                        </div>
                                    </div>
                                    @if($reservation->passengers->count() > 0)
                                    <div class="mt-3">
                                        <span class="text-slate-500 text-sm">Passengers:</span>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($reservation->passengers as $passenger)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                                                {{ $passenger->passenger_name ?? $passenger->passenger->name ?? 'Unknown' }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4 text-right">
                                    <div class="text-xs text-slate-400 mb-1">Status</div>
                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-3 h-3 mr-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        Approved
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-200/60 dark:border-darkmode-400">
                                <div class="text-xs text-slate-400">
                                    Requested by: {{ $reservation->user->name ?? 'N/A' }}
                                </div>
                                <div class="flex space-x-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="viewTripDetails({{ $reservation->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        View Details
                                    </button>
                                    <button class="btn btn-success btn-sm" onclick="startTrip({{ $reservation->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play w-4 h-4 mr-1"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                        Start Trip
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-darkmode-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x text-slate-400"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="10" y1="14" x2="10" y2="18"></line><line x1="14" y1="14" x2="14" y2="18"></line></svg>
                        </div>
                        <div class="text-lg font-medium text-slate-600 dark:text-slate-400 mb-2">No Approved Trips</div>
                        <div class="text-slate-500">You don't have any approved vehicle reservations at the moment.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END: Current Trip Card -->
    
    <!-- BEGIN: All Reservations Debug Section -->
    @if(config('app.debug') && $allReservations->count() > 0)
    <div class="col-span-12 mt-6">
        <div class="intro-y box">
            <div class="flex items-center px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">All Reservations in System (Debug)</h2>
            </div>
            <div class="p-5">
                <div class="space-y-4">
                    @foreach($allReservations->take(5) as $reservation)
                    <div class="border rounded-lg p-4 bg-slate-50">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-slate-500">ID:</span>
                                <div class="font-medium">{{ $reservation->id }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Driver ID:</span>
                                <div class="font-medium">{{ $reservation->driver_user_id ?? 'Not Set' }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Status:</span>
                                <div class="font-medium">{{ $reservation->status ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">Vehicle:</span>
                                <div class="font-medium">{{ $reservation->vehicle->plate_number ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm mt-2">
                            <div>
                                <span class="text-slate-500">From:</span>
                                <div class="font-medium">{{ $reservation->requested_name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <span class="text-slate-500">To:</span>
                                <div class="font-medium">{{ $reservation->destination ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-400 mt-2">
                            Requested by: {{ $reservation->user->name ?? 'N/A' }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($allReservations->count() > 5)
                <div class="text-center mt-4 text-slate-500">
                    Showing first 5 of {{ $allReservations->count() }} reservations
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    <!-- END: All Reservations Debug Section -->
</div>

<!-- BEGIN: Trip Details Modal -->
<div id="trip-details-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Trip Details</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body" id="trip-details-content">
                <!-- Trip details will be loaded here -->
            </div>
        </div>
    </div>
</div>
<!-- END: Trip Details Modal -->

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="Trip started successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="error" 
    type="error" 
    title="Error!" 
    message="An error occurred while processing your request" 
    :showButton="false" 
    :autoHide="true" 
    :duration="5000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="info" 
    type="info" 
    title="Information" 
    message="Live tracking system is ready" 
    :showButton="false" 
    :autoHide="true" 
    :duration="4000" 
    position="right" 
    gravity="top" 
/>
<!-- END: Notification Toasts -->
@endsection

@push('scripts')
    <script src="{{ asset('js/live-tracking/live-tracking.js') }}"></script>
@endpush