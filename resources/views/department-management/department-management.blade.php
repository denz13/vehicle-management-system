@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Department Management
    </h2>
</div>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-department-modal">Add New Department</button>
        <div class="hidden md:block mx-auto text-slate-500">
            @if($departments->total() > 0)
                Showing {{ $departments->firstItem() }} to {{ $departments->lastItem() }} of {{ $departments->total() }} departments
            @else
                No departments found
            @endif
        </div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" class="form-control w-56 box pr-10" placeholder="Search..." id="search-departments">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
            </div>
        </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">DEPARTMENT NAME</th>
                    <th class="whitespace-nowrap">DESCRIPTION</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">CREATED AT</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody id="departments-table-body">
                @forelse($departments as $department)
                <tr class="intro-x">
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ $department->department_name }}</a>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            {{ $department->description ?: 'No description' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center {{ $department->status === 'active' ? 'text-success' : 'text-danger' }}">
                            @if($department->status === 'active')
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Inactive
                            @endif
                        </div>
                    </td>
                    <td class="text-center">{{ $department->created_at->format('M d, Y') }}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" onclick="editDepartment({{ $department->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" class="lucide lucide-edit w-4 h-4 mr-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;" onclick="deleteDepartment({{ $department->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-500">No departments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    <x-pagination 
        :currentPage="$departments->currentPage()"
        :totalPages="$departments->lastPage()"
        :perPage="$departments->perPage()"
        :perPageOptions="[10, 25, 50, 100]"
        :showPerPageSelector="true"
        :showFirstLast="true"
    />
</div>

<!-- BEGIN: Add Department Modal -->
<div id="add-department-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Department</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <input type="hidden" name="_method" value="POST">
                <div class="col-span-12">
                    <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                    <input id="department_name" type="text" class="form-control" placeholder="Enter department name" required>
                    <div class="text-danger text-xs mt-1" id="department_name_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" class="form-control" placeholder="Enter department description" rows="3"></textarea>
                    <div class="text-danger text-xs mt-1" id="description_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="status" class="form-select" required>
                        <option value="">Select status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="text-danger text-xs mt-1" id="status_error"></div>
                </div>
            </div>
            <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                <button type="button" class="btn btn-primary w-20" onclick="saveDepartment()">Save</button>
            </div>
            <!-- END: Modal Footer -->
        </div>
    </div>
</div>
<!-- END: Add Department Modal -->

<!-- BEGIN: Edit Department Modal -->
<div id="edit-department-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Department</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <input type="hidden" id="edit_department_id">
                <input type="hidden" name="_method" value="PUT">
                <div class="col-span-12">
                    <label for="edit_department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                    <input id="edit_department_name" type="text" class="form-control" placeholder="Enter department name" required>
                    <div class="text-danger text-xs mt-1" id="edit_department_name_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_description" class="form-label">Description</label>
                    <textarea id="edit_description" class="form-control" placeholder="Enter department description" rows="3"></textarea>
                    <div class="text-danger text-xs mt-1" id="edit_description_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="edit_status" class="form-select" required>
                        <option value="">Select status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="text-danger text-xs mt-1" id="edit_status_error"></div>
                </div>
            </div>
            <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                <button type="button" class="btn btn-primary w-20" onclick="updateDepartment()">Update</button>
            </div>
            <!-- END: Modal Footer -->
        </div>
    </div>
</div>
<!-- END: Edit Department Modal -->

<!-- BEGIN: Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Are you sure?</div>
                    <div class="text-slate-500 mt-2">Do you really want to delete this department? <br>This process cannot be undone.</div>
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
    message="Department saved successfully" 
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
    message="Department management system is ready" 
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
    message="Department updated successfully" 
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
    message="Department deleted successfully" 
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
    <script src="{{ asset('js/department-management/department-management.js') }}"></script>
@endpush