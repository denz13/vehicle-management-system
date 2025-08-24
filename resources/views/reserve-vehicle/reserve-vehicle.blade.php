@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Vehicle Reservation
    </h2>
</div>
<div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500">
            @if($vehicles->total() > 0)
                Showing {{ $vehicles->firstItem() }} to {{ $vehicles->lastItem() }} of {{ $vehicles->total() }} available vehicles
            @else
                No available vehicles found
            @endif
                            </div>
                            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                                <div class="w-56 relative text-slate-500">
                <input type="text" class="form-control w-56 box pr-10" placeholder="Search vehicles..." id="search-vehicles">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
                                </div>
                            </div>
                        </div>

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">VEHICLE IMAGE</th>
                    <th class="whitespace-nowrap">VEHICLE NAME</th>
                    <th class="whitespace-nowrap">MODEL & COLOR</th>
                    <th class="whitespace-nowrap">PLATE NUMBER</th>
                    <th class="text-center whitespace-nowrap">CAPACITY</th>
                    <th class="text-center whitespace-nowrap">DATE ACQUIRED</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody id="vehicles-table-body">
                @forelse($vehicles as $vehicle)
                <tr class="intro-x">
                    <td>
                        <div class="flex">
                            @if($vehicle->vehicle_image)
                                <div class="w-10 h-10 image-fit zoom-in">
                                    <img alt="Vehicle Image" class="tooltip rounded-full" src="{{ asset('storage/vehicles/' . $vehicle->vehicle_image) }}" title="{{ $vehicle->vehicle_name }}">
                                    </div>
                            @else
                                <div class="w-10 h-10 image-fit zoom-in bg-slate-200 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-slate-400"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>
                                    </div>
                            @endif
                                </div>
                    </td>
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ $vehicle->vehicle_name }}</a>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ $vehicle->model }}</div>
                            <div class="text-xs">{{ $vehicle->vehicle_color }}</div>
                                </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <span class="font-medium">{{ $vehicle->plate_number }}</span>
                            </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            <span class="font-medium">{{ $vehicle->capacity }}</span>
                        </div>
                    </td>
                    <td class="text-center">{{ $vehicle->date_acquired ? \Carbon\Carbon::parse($vehicle->date_acquired)->format('M d, Y') : 'N/A' }}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            @if($vehicle->status === 'active')
                                <div class="flex items-center text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active
                                    </div>
                            @elseif($vehicle->status === 'maintenance')
                                <div class="flex items-center text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="wrench" data-lucide="wrench" class="lucide lucide-wrench w-4 h-4 mr-2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg> Maintenance
                                    </div>
                            @else
                                <div class="flex items-center text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Inactive
                                </div>
                            @endif
                                </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <!-- <a class="flex items-center mr-3" href="javascript:;" onclick="viewVehicleDetails({{ $vehicle->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Details
                            </a> -->
                            <a class="flex items-center text-success" href="javascript:;" onclick="reserveVehicle({{ $vehicle->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="calendar-plus" class="lucide lucide-calendar-plus w-4 h-4 mr-1"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><path d="M12 9v6"></path><path d="M9 12h6"></path></svg> Reserve
                            </a>
                            </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-slate-500">No available vehicles found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
                        </div>
    <!-- END: Data List -->
    
    <!-- BEGIN: Pagination -->
    @if($vehicles->hasPages())
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            {{ $vehicles->links() }}
        </nav>
        <select class="w-20 form-select box mt-3 sm:mt-0" id="per-page-selector">
            <option value="12" {{ $vehicles->perPage() == 12 ? 'selected' : '' }}>12</option>
            <option value="24" {{ $vehicles->perPage() == 24 ? 'selected' : '' }}>24</option>
            <option value="36" {{ $vehicles->perPage() == 36 ? 'selected' : '' }}>36</option>
            <option value="48" {{ $vehicles->perPage() == 48 ? 'selected' : '' }}>48</option>
        </select>
                                    </div>
    @endif
    <!-- END: Pagination -->
                                    </div>

<!-- BEGIN: Vehicle Details Modal -->
<div id="vehicle-details-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Vehicle Details</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
                                </div>
            <div class="modal-body" id="vehicle-details-content">
                <!-- Vehicle details will be loaded here -->
                                </div>
                            </div>
                        </div>
                                    </div>
<!-- END: Vehicle Details Modal -->

<!-- BEGIN: Reserve Vehicle Modal -->
<div id="reserve-vehicle-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Reserve Vehicle</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
                                    </div>
            <div class="modal-body">
                <form id="reservation-form" onsubmit="console.log('Form submitted!'); return false;">
                    <input type="hidden" id="reserve_vehicle_id" name="vehicle_id">
                    <input type="hidden" id="requested_user_id" name="requested_user_id" value="{{ Auth::id() }}">
                    
                    <!-- Basic Information Section -->
                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-3 text-primary">Basic Information</h3>
                                </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="driver" class="form-label">Driver <span class="text-danger">*</span></label>
                            <select id="driver" name="driver" class="form-select" required>
                                <option value="">Select Driver</option>
                                @foreach($users as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-xs mt-1" id="driver_error"></div>
                                </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="start_datetime" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                            <input id="start_datetime" name="start_datetime" type="datetime-local" class="form-control" required>
                            <div class="text-danger text-xs mt-1" id="start_datetime_error"></div>
                            </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="end_datetime" class="form-label">End Date & Time <span class="text-danger">*</span></label>
                            <input id="end_datetime" name="end_datetime" type="datetime-local" class="form-control" required>
                            <div class="text-danger text-xs mt-1" id="end_datetime_error"></div>
                            <div class="text-slate-500 text-xs mt-1">
                                <span class="font-medium">Tip:</span> You can select the same date with different times for same-day trips
                                    </div>
                                </div>
                        
                        <div class="col-span-12">
                            <label for="reason" class="form-label">Purpose/Reason <span class="text-danger">*</span></label>
                            <textarea id="reason" name="reason" class="form-control" placeholder="Enter the purpose of vehicle reservation" rows="3" required></textarea>
                            <div class="text-danger text-xs mt-1" id="reason_error"></div>
                                </div>
                            </div>
                    
                    <!-- Destination & Location Section -->
                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mb-3 text-primary">Destination & Location</h3>
                        </div>
                        
                        <div class="col-span-12">
                            <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                            <input id="destination" name="destination" type="text" class="form-control" placeholder="Enter destination address" required>
                            <div class="text-danger text-xs mt-1" id="destination_error"></div>
                                    </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input id="longitude" name="longitude" type="text" class="form-control" placeholder="Automatically generated longitude" readonly>
                            <div class="text-danger text-xs mt-1" id="longitude_error"></div>
                                    </div>
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input id="latitude" name="latitude" type="text" class="form-control" placeholder="Automatically generated latitude" readonly>
                            <div class="text-danger text-xs mt-1" id="latitude_error"></div>
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
                                <div id="map" class="w-full h-80 rounded-md border border-slate-200" style="min-height: 320px;"></div>
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
                        
                        <div class="col-span-12 md:col-span-6">
                            <label for="reservation_type_id" class="form-label">Reservation Type <span class="text-danger">*</span></label>
                            <select id="reservation_type_id" name="reservation_type_id" class="form-select" required>
                                <option value="">Select reservation type</option>
                                @foreach($reservationTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->reservation_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger text-xs mt-1" id="reservation_type_id_error"></div>
                                </div>
                        
                        <!-- Dynamic Passenger List -->
                        <div class="col-span-12">
                            <label for="passenger-select" class="form-label">Passenger(s) <span class="text-danger">*</span></label>
                            
                            <x-tom-select 
                                id="passenger-select"
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
                <button type="button" class="btn btn-success w-20" onclick="submitReservation()">Reserve</button>
                <button type="button" class="btn btn-warning btn-sm" onclick="testSubmitFunction()">Test Function</button>
                                </div>
                            </div>
                        </div>
                                    </div>
<!-- END: Reserve Vehicle Modal -->

<!-- BEGIN: QR Code Modal -->
<div id="qr-code-modal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Reservation QR Code</h2>
            </div>
            <div class="modal-body">
                <div id="qr-code-content">
                    <!-- QR code content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: QR Code Modal -->

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="Vehicle reserved successfully" 
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
    message="Vehicle reservation system is ready" 
    :showButton="false" 
    :autoHide="true" 
    :duration="4000" 
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
<!-- END: Notification Toasts -->
@endsection

@push('scripts')
    <script src="{{ asset('js/reserve-vehicle/reserve-vehicle.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM2-ikIyV0IMgQ31Rtpn_XBAMTm9wKup4&libraries=places&callback=initGoogleMaps" async defer></script>
@endpush
