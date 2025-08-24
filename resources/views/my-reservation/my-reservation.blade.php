@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        My Reservations
    </h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500">
            @if($reservations->total() > 0)
                Showing {{ $reservations->firstItem() }} to {{ $reservations->lastItem() }} of {{ $reservations->total() }} reservations
            @else
                No reservations found
            @endif
        </div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" class="form-control w-56 box pr-10" placeholder="Search reservations..." id="search-reservations">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
            </div>
        </div>
    </div>

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">VEHICLE</th>
                    <th class="whitespace-nowrap">DESTINATION</th>
                    <th class="whitespace-nowrap">DRIVER</th>
                    <th class="whitespace-nowrap">DATE & TIME</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">RESERVATION TYPE</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody id="reservations-table-body">
                @forelse($reservations as $reservation)
                <tr class="intro-x">
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 image-fit zoom-in bg-slate-200 rounded-full flex items-center justify-center">
                                @if($reservation->vehicle && $reservation->vehicle->vehicle_image)
                                    <img src="{{ asset('storage/vehicles/' . $reservation->vehicle->vehicle_image) }}" alt="{{ $reservation->vehicle->vehicle_name }}" class="w-full h-full object-cover rounded-full">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <div class="font-medium">{{ $reservation->vehicle->vehicle_name ?? 'N/A' }}</div>
                                <div class="text-slate-500 text-xs">{{ $reservation->vehicle->plate_number ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ $reservation->destination }}</div>
                            <div class="text-xs">{{ $reservation->longitude }}, {{ $reservation->latitude }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="font-medium">{{ $reservation->driver }}</div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('M d, Y') }}</div>
                            <div class="text-xs">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_datetime)->format('h:i A') }}</div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            @if($reservation->status === 'pending')
                                <div class="flex items-center text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline></svg> Pending
                                </div>
                            @elseif($reservation->status === 'approved')
                                <div class="flex items-center text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4 mr-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Approved
                                </div>
                            @elseif($reservation->status === 'rejected')
                                <div class="flex items-center text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Rejected
                                </div>
                            @elseif($reservation->status === 'completed')
                                <div class="flex items-center text-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Completed
                                </div>
                            @elseif($reservation->status === 'cancelled')
                                <div class="flex items-center text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Cancelled
                                </div>
                            @else
                                <div class="flex items-center text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> {{ ucfirst($reservation->status) }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="font-medium">{{ $reservation->reservation_type->reservation_name ?? 'N/A' }}</div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" onclick="viewReservationDetails({{ $reservation->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Details
                            </a>
                            <a class="flex items-center mr-3" href="javascript:;" onclick="updateReservation({{ $reservation->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4 mr-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 10.5-10.5z"></path></svg> Update
                            </a>
                            @if($reservation->status === 'pending')
                                <a class="flex items-center text-danger" href="javascript:;" onclick="cancelReservation({{ $reservation->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Cancel
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-slate-500">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <div class="text-lg font-medium">No reservations yet</div>
                            <div class="text-sm text-slate-500 mt-1">Start by reserving a vehicle</div>
                            <a href="{{ route('reserve-vehicle.index') }}" class="btn btn-primary mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-plus w-4 h-4 mr-2"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><path d="M12 9v6"></path><path d="M9 12h6"></path></svg>
                                Reserve a Vehicle
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    
    @if($reservations->total() > 0)
        <x-pagination 
            :currentPage="$reservations->currentPage()"
            :totalPages="$reservations->lastPage()"
            :perPage="$reservations->perPage()"
            :perPageOptions="[10, 25, 50, 100]"
            :showPerPageSelector="true"
            :showFirstLast="true"
        />
    @endif
</div>

<!-- BEGIN: Reservation Details Modal -->
<div id="reservation-details-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Reservation Details</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body">
                <div id="reservation-details-content">
                    <!-- Reservation details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Reservation Details Modal -->

<!-- BEGIN: Cancel Confirmation Modal -->
<div id="cancel-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Cancel Reservation?</div>
                    <div class="text-slate-500 mt-2">Do you really want to cancel this reservation? <br>This action cannot be undone.</div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">No, Keep It</button>
                    <button type="button" class="btn btn-danger w-24" id="confirm-cancel-btn">Yes, Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Cancel Confirmation Modal -->

<!-- BEGIN: Update Reservation Modal -->
<div id="update-reservation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Update Reservation</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body">
                <form id="update-reservation-form" onsubmit="submitUpdateForm(); return false;">
                    <!-- Basic Information Section -->
                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-3 text-primary">Basic Information</h3>
                        </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="update_driver" class="form-label">Driver <span class="text-danger">*</span></label>
                            <select id="update_driver" name="driver" class="form-select" required>
                                <option value="">Select Driver</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label for="update_reservation_type_id" class="form-label">Reservation Type <span class="text-danger">*</span></label>
                            <select id="update_reservation_type_id" name="reservation_type_id" class="form-select" required>
                                <option value="">Select reservation type</option>
                                @foreach($reservationTypes ?? [] as $type)
                                    <option value="{{ $type->id }}">{{ $type->reservation_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="update_start_datetime" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                            <input id="update_start_datetime" name="start_datetime" type="datetime-local" class="form-control" required>
                        </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="update_end_datetime" class="form-label">End Date & Time <span class="text-danger">*</span></label>
                            <input id="update_end_datetime" name="end_datetime" type="datetime-local" class="form-control" required>
                            <div class="text-slate-500 text-xs mt-1">
                                <span class="font-medium">Tip:</span> You can select the same date with different times for same-day trips
                            </div>
                        </div>
                        
                        <div class="col-span-12">
                            <label for="update_reason" class="form-label">Purpose/Reason <span class="text-danger">*</span></label>
                            <textarea id="update_reason" name="reason" class="form-control" placeholder="Enter the purpose of vehicle reservation" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <!-- Destination & Location Section -->
                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-3 text-primary">Destination & Location</h3>
                        </div>
                        
                        <div class="col-span-12">
                            <label for="update_destination" class="form-label">Destination <span class="text-danger">*</span></label>
                            <input id="update_destination" name="destination" type="text" class="form-control" placeholder="Enter destination address" required>
                        </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="update_longitude" class="form-label">Longitude</label>
                            <input id="update_longitude" name="longitude" type="text" class="form-control" placeholder="Automatically generated longitude" readonly>
                        </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="update_latitude" class="form-label">Latitude</label>
                            <input id="update_latitude" name="latitude" type="text" class="form-control" placeholder="Automatically generated latitude" readonly>
                        </div>
                    </div>
                    
                    <!-- Google Maps Section -->
                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-3 text-primary">Map Location</h3>
                            <p class="text-slate-500 text-sm mb-3">Click on the map to set coordinates</p>
                        </div>
                        
                        <div class="col-span-12">
                            <div class="relative">
                                <div id="update_map" class="w-full h-80 rounded-md border border-slate-200" style="min-height: 320px;"></div>
                                <div class="mt-2 text-xs text-slate-500">
                                    <span class="font-medium">Tip:</span> Click on the map to set coordinates
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Passenger Information Section -->
                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-3 text-primary">Passenger Information</h3>
                        </div>
                            
                        <!-- Dynamic Passenger List -->
                        <div class="col-span-12">
                            <label for="update_passengers" class="form-label">Passenger(s) <span class="text-danger">*</span></label>
                            
                            <x-tom-select 
                                id="update_passengers"
                                name="passengers"
                                placeholder="Select Passenger(s)"
                                :multiple="true"
                                :required="true"
                                class="w-full"
                                :options="$users->pluck('name', 'id')->toArray()"
                                :selected="[]"
                                :maxItems="null"
                                :plugins="['remove_button']"
                                :autoInit="false"
                            />
                            
                            <span class="font-medium">Note:</span> Select one or more passengers from the list. You can search and select multiple users.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                <button type="button" class="btn btn-primary w-20" onclick="submitUpdateForm()">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Update Reservation Modal -->

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="Action completed successfully" 
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
    message="My reservations system is ready" 
    :showButton="false" 
    :autoHide="true" 
    :duration="4000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="cancel_success" 
    type="success" 
    title="Success!" 
    message="Reservation cancelled successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="validation_error" 
    type="error" 
    title="Validation Error!" 
    message="Please check the form for errors" 
    :showButton="false" 
    :autoHide="true" 
    :duration="5000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="update_success" 
    type="success" 
    title="Success!" 
    message="Reservation updated successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>
<!-- END: Notification Toasts -->
@endsection

@push('scripts')
    <script src="{{ asset('js/my-reservation/my-reservation.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM2-ikIyV0IMgQ31Rtpn_XBAMTm9wKup4&libraries=places&callback=initUpdateGoogleMaps" async defer></script>
@endpush