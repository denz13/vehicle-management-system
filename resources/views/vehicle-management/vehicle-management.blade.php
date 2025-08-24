@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Vehicle Management
    </h2>
</div>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-vehicle-modal">Add New Vehicle</button>
        <div class="hidden md:block mx-auto text-slate-500">
            @if($vehicles->total() > 0)
                Showing {{ $vehicles->firstItem() }} to {{ $vehicles->lastItem() }} of {{ $vehicles->total() }} vehicles
            @else
                No vehicles found
            @endif
        </div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="search-vehicles">
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
                            <a class="flex items-center mr-3" href="javascript:;" onclick="editVehicle({{ $vehicle->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" class="lucide lucide-edit w-4 h-4 mr-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;" onclick="deleteVehicle({{ $vehicle->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-slate-500">No vehicles found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    <x-pagination 
        :currentPage="$vehicles->currentPage()"
        :totalPages="$vehicles->lastPage()"
        :perPage="$vehicles->perPage()"
        :perPageOptions="[10, 25, 50, 100]"
        :showPerPageSelector="true"
        :showFirstLast="true"
    />
</div>

<!-- BEGIN: Add Vehicle Modal -->
<div id="add-vehicle-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Vehicle</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <input type="hidden" name="_method" value="POST">
                <div class="col-span-6">
                    <label for="vehicle_name" class="form-label">Vehicle Name <span class="text-danger">*</span></label>
                    <input id="vehicle_name" type="text" class="form-control" placeholder="Enter vehicle name" required>
                    <div class="text-danger text-xs mt-1" id="vehicle_name_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="vehicle_color" class="form-label">Vehicle Color <span class="text-danger">*</span></label>
                    <input id="vehicle_color" type="text" class="form-control" placeholder="Enter vehicle color" required>
                    <div class="text-danger text-xs mt-1" id="vehicle_color_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                    <input id="model" type="text" class="form-control" placeholder="Enter vehicle model" required>
                    <div class="text-danger text-xs mt-1" id="model_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="plate_number" class="form-label">Plate Number <span class="text-danger">*</span></label>
                    <input id="plate_number" type="text" class="form-control" placeholder="Enter plate number" required>
                    <div class="text-danger text-xs mt-1" id="plate_number_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                    <input id="capacity" type="number" class="form-control" placeholder="Enter capacity" min="1" required>
                    <div class="text-danger text-xs mt-1" id="capacity_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="date_acquired" class="form-label">Date Acquired <span class="text-danger">*</span></label>
                    <input id="date_acquired" type="date" class="form-control" required>
                    <div class="text-danger text-xs mt-1" id="date_acquired_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="status" class="form-select" required>
                        <option value="">Select status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                    <div class="text-danger text-xs mt-1" id="status_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="vehicle_image" class="form-label">Vehicle Image</label>
                    <input id="vehicle_image" type="file" class="form-control" accept="image/*">
                    <div class="text-danger text-xs mt-1" id="vehicle_image_error"></div>
                </div>
            </div>
            <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                <button type="button" class="btn btn-primary w-20" onclick="saveVehicle()">Save</button>
            </div>
            <!-- END: Modal Footer -->
        </div>
    </div>
</div>
<!-- END: Add Vehicle Modal -->

<!-- BEGIN: Edit Vehicle Modal -->
<div id="edit-vehicle-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Vehicle</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <input type="hidden" id="edit_vehicle_id">
                <input type="hidden" name="_method" value="PUT">
                <div class="col-span-6">
                    <label for="edit_vehicle_name" class="form-label">Vehicle Name <span class="text-danger">*</span></label>
                    <input id="edit_vehicle_name" type="text" class="form-control" placeholder="Enter vehicle name" required>
                    <div class="text-danger text-xs mt-1" id="edit_vehicle_name_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_vehicle_color" class="form-label">Vehicle Color <span class="text-danger">*</span></label>
                    <input id="edit_vehicle_color" type="text" class="form-control" placeholder="Enter vehicle color" required>
                    <div class="text-danger text-xs mt-1" id="edit_vehicle_color_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_model" class="form-label">Model <span class="text-danger">*</span></label>
                    <input id="edit_model" type="text" class="form-control" placeholder="Enter vehicle model" required>
                    <div class="text-danger text-xs mt-1" id="edit_model_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_plate_number" class="form-label">Plate Number <span class="text-danger">*</span></label>
                    <input id="edit_plate_number" type="text" class="form-control" placeholder="Enter plate number" required>
                    <div class="text-danger text-xs mt-1" id="edit_plate_number_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                    <input id="edit_capacity" type="number" class="form-control" placeholder="Enter capacity" min="1" required>
                    <div class="text-danger text-xs mt-1" id="edit_capacity_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_date_acquired" class="form-label">Date Acquired <span class="text-danger">*</span></label>
                    <input id="edit_date_acquired" type="date" class="form-control" required>
                    <div class="text-danger text-xs mt-1" id="edit_date_acquired_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="edit_status" class="form-select" required>
                        <option value="">Select status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                    <div class="text-danger text-xs mt-1" id="edit_status_error"></div>
                </div>
                <div class="col-span-6">
                    <label for="edit_vehicle_image" class="form-label">Vehicle Image</label>
                    <input id="edit_vehicle_image" type="file" class="form-control" accept="image/*">
                    <div class="text-danger text-xs mt-1" id="edit_vehicle_image_error"></div>
                </div>
                <div class="col-span-12" id="current_image_display" style="display: none;">
                    <label class="form-label">Current Image</label>
                    <div class="mt-2">
                        <img id="current_image" src="" alt="Current Vehicle Image" class="w-32 h-32 object-cover rounded border">
                    </div>
                </div>
            </div>
            <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                <button type="button" class="btn btn-primary w-20" onclick="updateVehicle()">Update</button>
            </div>
            <!-- END: Modal Footer -->
        </div>
    </div>
</div>
<!-- END: Edit Vehicle Modal -->

<!-- BEGIN: Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Are you sure?</div>
                    <div class="text-slate-500 mt-2">Do you really want to delete this vehicle? <br>This process cannot be undone.</div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                    <button type="button" class="btn btn-danger w-24" id="confirm-delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Confirmation Modal -->

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="Vehicle saved successfully" 
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
    message="Vehicle management system is ready" 
    :showButton="false" 
    :autoHide="true" 
    :duration="4000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="edit_success" 
    type="success" 
    title="Success!" 
    message="Vehicle updated successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="delete_success" 
    type="success" 
    title="Success!" 
    message="Vehicle deleted successfully" 
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
<!-- END: Notification Toasts -->
@endsection

@push('scripts')
    <script src="{{ asset('js/vehicle-management/vehicle-management.js') }}"></script>
@endpush
