@extends('layout._partials.master')

@section('content')
<!-- Hidden user data for JavaScript -->
<div data-user-data style="display: none;">
    {
        "name": "{{ auth()->user()->name }}",
        "email": "{{ auth()->user()->email }}",
        "contact_number": "{{ auth()->user()->contact_number }}",
        "address": "{{ auth()->user()->address }}",
        "date_of_birth": "{{ auth()->user()->date_of_birth }}",
        "gender": "{{ auth()->user()->gender }}"
    }
</div>
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Profile Management
    </h2>
</div>
<div class="intro-y box px-5 pt-5 mt-5">
    <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
        <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                @if(auth()->user()->photo_url)
                    <img alt="Profile Photo" class="rounded-full w-full h-full object-cover" src="{{ auth()->user()->photo_url }}">
                @else
                    <div class="w-full h-full bg-slate-200 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                @endif
                <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2 cursor-pointer" onclick="document.getElementById('profile-photo-input').click()"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera w-4 h-4 text-white" data-lucide="camera">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-3l-2.5-3z"></path>
                        <circle cx="12" cy="13" r="3"></circle>
                    </svg> 
                </div>
                <input type="file" id="profile-photo-input" accept="image/*" style="display: none;" onchange="confirmPhotoUpload(this)">
            </div>
            <div class="ml-5">
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ auth()->user()->name }}</div>
                <div class="text-slate-500">{{ auth()->user()->position ? auth()->user()->position->position_name : 'No Position' }}</div>
                <div class="text-slate-400 text-sm">{{ auth()->user()->department ? auth()->user()->department->department_name : 'No Department' }}</div>
            </div>
        </div>
        <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
            <div class="font-medium text-center lg:text-left lg:mt-3">Contact Details</div>
            <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                <div class="truncate sm:whitespace-normal flex items-center"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-4 h-4 mr-2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg> 
                    {{ auth()->user()->email }}
                </div>
                @if(auth()->user()->contact_number)
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-4 h-4 mr-2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg> 
                    {{ auth()->user()->contact_number }}
                </div>
                @endif
                @if(auth()->user()->address)
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 mr-2">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg> 
                    {{ auth()->user()->address }}
                </div>
                @endif
            </div>
        </div>
        <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0">
            <div class="font-medium text-center lg:text-left lg:mt-5">Personal Information</div>
            <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                @if(auth()->user()->date_of_birth)
                <div class="truncate sm:whitespace-normal flex items-center"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4 mr-2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg> 
                    {{ \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('M d, Y') }}
                </div>
                @endif
                @if(auth()->user()->gender)
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-4 h-4 mr-2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg> 
                    {{ ucfirst(auth()->user()->gender) }}
                </div>
                @endif
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle w-4 h-4 mr-2 {{ auth()->user()->active ? 'text-success' : 'text-danger' }}">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg> 
                    Status: <span class="ml-1 {{ auth()->user()->active ? 'text-success' : 'text-danger' }}">{{ auth()->user()->active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
        <li id="dashboard-tab" class="nav-item" role="presentation"> 
            <a href="javascript:;" class="nav-link py-4 active" data-tw-target="#dashboard" aria-controls="dashboard" aria-selected="true" role="tab"> Information </a> 
        </li>
        <li id="activities-tab" class="nav-item" role="presentation"> 
            <a href="javascript:;" class="nav-link py-4" data-tw-target="#activities" aria-selected="false" role="tab"> Change Password </a> 
        </li>
    </ul>
</div>
<div class="intro-y tab-content mt-5">
    <div id="dashboard" class="tab-pane active" role="tabpanel" aria-labelledby="dashboard-tab">
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: User Information -->
            <div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        User Information
                    </h2>
                    <button class="btn btn-primary btn-sm" onclick="editProfile()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4 mr-1">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Profile
                    </button>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="font-medium w-24">Name:</span>
                            <span class="ml-3">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Email:</span>
                            <span class="ml-3">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Department:</span>
                            <span class="ml-3">{{ auth()->user()->department ? auth()->user()->department->department_name : 'Not Assigned' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Position:</span>
                            <span class="ml-3">{{ auth()->user()->position ? auth()->user()->position->position_name : 'Not Assigned' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Date of Birth:</span>
                            <span class="ml-3">{{ auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->format('M d, Y') : 'Not Set' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Gender:</span>
                            <span class="ml-3">{{ auth()->user()->gender ? ucfirst(auth()->user()->gender) : 'Not Set' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Contact:</span>
                            <span class="ml-3">{{ auth()->user()->contact_number ?: 'Not Set' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Address:</span>
                            <span class="ml-3">{{ auth()->user()->address ?: 'Not Set' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium w-24">Status:</span>
                            <span class="ml-3 {{ auth()->user()->active ? 'text-success' : 'text-danger' }}">
                                {{ auth()->user()->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- END: User Information -->
            
            <!-- BEGIN: Account Statistics -->
            <div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Account Statistics
                    </h2>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Account Status</span>
                            <span class="px-2 py-1 rounded-full text-xs {{ auth()->user()->active ? 'bg-success/20 text-success' : 'bg-danger/20 text-danger' }}">
                                {{ auth()->user()->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Email Verified</span>
                            <span class="px-2 py-1 rounded-full text-xs {{ auth()->user()->email_verified_at ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning' }}">
                                {{ auth()->user()->email_verified_at ? 'Verified' : 'Not Verified' }}
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- END: Account Statistics -->
        </div>
    </div>
    
    <div id="activities" class="tab-pane" role="tabpanel" aria-labelledby="activities-tab">
        <div class="intro-y box">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Change Password
                </h2>
            </div>
            <div class="p-5">
                <form id="change-password-form">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input id="current_password" type="password" class="form-control" placeholder="Enter current password" required>
                            <div class="text-danger text-xs mt-1" id="current_password_error"></div>
                        </div>
                        <div class="col-span-12">
                            <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <input id="new_password" type="password" class="form-control" placeholder="Enter new password" required>
                            <div class="text-danger text-xs mt-1" id="new_password_error"></div>
                        </div>
                        <div class="col-span-12">
                            <label for="confirm_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input id="confirm_password" type="password" class="form-control" placeholder="Confirm new password" required>
                            <div class="text-danger text-xs mt-1" id="confirm_password_error"></div>
                        </div>
                        <div class="col-span-12">
                            <button type="submit" class="btn btn-primary w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key w-4 h-4 mr-2">
                                    <rect x="1" y="1" width="20" height="20" rx="2.18" ry="2.18"></rect>
                                    <line x1="7" y1="11" x2="11" y2="7"></line>
                                    <line x1="11" y1="7" x2="16" y2="12"></line>
                                    <line x1="13" y1="17" x2="8" y2="22"></line>
                                    <line x1="2" y1="22" x2="7" y2="17"></line>
                                </svg>
                                Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN: Edit Profile Modal -->
<div id="edit-profile-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Profile</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="file" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="edit_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input id="edit_name" type="text" class="form-control" value="{{ auth()->user()->name }}" required>
                    <div class="text-danger text-xs mt-1" id="edit_name_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input id="edit_email" type="email" class="form-control" value="{{ auth()->user()->email }}" required>
                    <div class="text-danger text-xs mt-1" id="edit_email_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_contact_number" class="form-label">Contact Number</label>
                    <input id="edit_contact_number" type="text" class="form-control" value="{{ auth()->user()->contact_number }}" placeholder="Enter contact number">
                    <div class="text-danger text-xs mt-1" id="edit_contact_number_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_address" class="form-label">Address</label>
                    <textarea id="edit_address" class="form-control" rows="3" placeholder="Enter address">{{ auth()->user()->address }}</textarea>
                    <div class="text-danger text-xs mt-1" id="edit_address_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_date_of_birth" class="form-label">Date of Birth</label>
                    <input id="edit_date_of_birth" type="date" class="form-control" value="{{ auth()->user()->date_of_birth }}">
                    <div class="text-danger text-xs mt-1" id="edit_date_of_birth_error"></div>
                </div>
                <div class="col-span-12">
                    <label for="edit_gender" class="form-label">Gender</label>
                    <select id="edit_gender" class="form-select">
                        <option value="">Select gender</option>
                        <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <div class="text-danger text-xs mt-1" id="edit_gender_error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                <button type="button" class="btn btn-primary w-20" onclick="updateProfile()">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Edit Profile Modal -->

<!-- BEGIN: Photo Upload Confirmation Modal -->
<div id="photo-upload-modal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body px-5 py-10">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto mb-4">
                        <img id="photo-preview" class="w-full h-full object-cover rounded-lg" src="" alt="Photo Preview">
                    </div>
                    <p class="text-slate-600 mb-4">Are you sure you want to upload this photo as your profile picture?</p>
                    <div class="text-sm text-slate-500">
                        <p><strong>File:</strong> <span id="photo-filename"></span></p>
                        <p><strong>Size:</strong> <span id="photo-filesize"></span></p>
                    </div>
                    <div class="flex justify-center space-x-2 mt-4">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20">Cancel</button>
                        <button type="button" class="btn btn-primary w-20" onclick="proceedWithPhotoUpload()">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Photo Upload Confirmation Modal -->

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="Profile updated successfully" 
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
    message="An error occurred while updating profile" 
    :showButton="false" 
    :autoHide="true" 
    :duration="5000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="edit_success" 
    type="success" 
    title="Success!" 
    message="Profile updated successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="password_success" 
    type="success" 
    title="Success!" 
    message="Password changed successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="password_error" 
    type="error" 
    title="Error!" 
    message="An error occurred while changing password" 
    :showButton="false" 
    :autoHide="true" 
    :duration="5000" 
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
    id="photo_success" 
    type="success" 
    title="Success!" 
    message="Profile photo updated successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="photo_error" 
    type="error" 
    title="Error!" 
    message="An error occurred while uploading photo" 
    :showButton="false" 
    :autoHide="true" 
    :duration="5000" 
    position="right" 
    gravity="top" 
/>
<!-- END: Notification Toasts -->

@endsection

@push('scripts')
    <script src="{{ asset('js/profile-management/profile-management.js') }}"></script>
@endpush