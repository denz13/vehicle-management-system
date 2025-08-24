@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Reservation Status Management
    </h2>
</div>

<!-- BEGIN: Status Overview Cards -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="calendar" class="report-box__icon text-primary"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $reservations->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Total Reservations</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="car" class="report-box__icon text-success"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $vehicles->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Total Vehicles</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="users" class="report-box__icon text-warning"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $users->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="tag" class="report-box__icon text-info"></i>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $reservationTypes->count() }}</div>
                <div class="text-base text-slate-500 mt-1">Reservation Types</div>
            </div>
        </div>
    </div>
</div>
<!-- END: Status Overview Cards -->

<!-- BEGIN: Vehicle Reservations Status -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="dropdown">
            <button class="dropdown-toggle btn btn-primary shadow-md mr-2" aria-expanded="false" data-tw-toggle="dropdown">
                <span class="flex items-center">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
                </span>
            </button>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content">
                    <li>
                        <a href="javascript:;" class="dropdown-item" onclick="applyFilters()">All Statuses</a>
                    </li>
                    @foreach($uniqueStatuses as $status)
                        <li>
                            <a href="javascript:;" class="dropdown-item" onclick="applyFilters('{{ $status }}')">
                                @switch($status)
                                    @case('pending')
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 mr-2 text-warning"><circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline></svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4 mr-2 text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle w-4 h-4 mr-2 text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-square w-4 h-4 mr-2 text-info"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-2 text-slate-500"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                        @break
                                    @default
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle w-4 h-4 mr-2 text-slate-400"><circle cx="12" cy="12" r="10"></circle></svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                @endswitch
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="hidden md:block mx-auto text-slate-500">
            @if($reservations->count() > 0)
                Showing {{ $reservations->count() }} reservations
            @else
                No reservations found
            @endif
        </div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" class="form-control w-56 box pr-10" placeholder="Search reservations..." id="search-reservations">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
            </div>
        </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">VEHICLE</th>
                    <th class="whitespace-nowrap">REQUESTER</th>
                    <th class="whitespace-nowrap">DRIVER</th>
                    <th class="whitespace-nowrap">DESTINATION</th>
                    <th class="whitespace-nowrap">DATES</th>
                    <th class="whitespace-nowrap">TYPE</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">PASSENGERS</th>
                    <th class="text-center whitespace-nowrap">ACTION</th>
                </tr>
            </thead>
            <tbody id="reservations-table-body">
                @forelse($reservations as $reservation)
                <tr class="intro-x">
                   
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ $reservation->vehicle->vehicle_name ?? 'N/A' }}</div>
                            <div class="text-xs">{{ $reservation->vehicle->plate_number ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ $reservation->user->name ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ $reservation->driver ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ Str::limit($reservation->destination, 30) }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('M d, Y') }}</div>
                            <div class="text-xs">{{ \Carbon\Carbon::parse($reservation->start_datetime)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_datetime)->format('h:i A') }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <div class="font-medium">{{ $reservation->reservation_type->reservation_name ?? 'N/A' }}</div>
                            <div class="text-xs">{{ Str::limit($reservation->reason, 25) }}</div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            @if($reservation->status === 'approved')
                                <div class="flex items-center text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle w-4 h-4 mr-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Approved
                                </div>
                            @elseif($reservation->status === 'pending')
                                <div class="flex items-center text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clock" data-lucide="clock" class="lucide lucide-clock w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline></svg> Pending
                                </div>
                            @elseif($reservation->status === 'rejected')
                                <div class="flex items-center text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Rejected
                                </div>
                            @elseif($reservation->status === 'completed')
                                <div class="flex items-center text-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Completed
                                </div>
                            @else
                                <div class="flex items-center text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="help-circle" data-lucide="help-circle" class="lucide lucide-help-circle w-4 h-4 mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> {{ ucfirst($reservation->status) }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            <button class="btn btn-sm btn-outline-primary" 
                                    data-tw-toggle="modal" 
                                    data-tw-target="#passengers-modal-{{ $reservation->id }}">
                                {{ $reservation->passengers->count() }} passengers
                            </button>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" onclick="viewReservationDetails({{ $reservation->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" class="lucide lucide-eye w-4 h-4 mr-1 text-info"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View Details
                            </a>
                            @if($reservation->status === 'pending')
                                <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#approve-modal-{{ $reservation->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check" class="lucide lucide-check w-4 h-4 mr-1 text-success"><polyline points="20,6 9,17 4,12"></polyline></svg> Approve
                                </a>
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#decline-modal-{{ $reservation->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4 mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Decline
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-8 text-slate-500">No reservations found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
</div>
<!-- END: Vehicle Reservations Status -->

<!-- BEGIN: Passengers Modal -->
@foreach($reservations as $reservation)
<div id="passengers-modal-{{ $reservation->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Passengers for Reservation #{{ $reservation->id }}</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                @if($reservation->passengers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">#</th>
                                    <th class="whitespace-nowrap">Passenger Name</th>
                                    <th class="whitespace-nowrap">Contact</th>
                                    <th class="whitespace-nowrap">Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservation->passengers as $index => $passenger)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $passenger->passenger->name ?? 'Unknown' }}</td>
                                    <td>{{ $passenger->passenger->email ?? 'N/A' }}</td>
                                    <td>{{ $passenger->passenger->department->department_name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-slate-300"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="m22 21-2-2-2 2"></path><path d="M16 16h6"></path></svg>
                        <p class="text-lg font-medium">No passengers found</p>
                        <p class="text-sm">This reservation has no passengers assigned.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-tw-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- END: Passengers Modal -->

<!-- BEGIN: Approve Modal -->
@foreach($reservations as $reservation)
<div id="approve-modal-{{ $reservation->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Approve Reservation</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <p class="text-lg font-medium mb-2">Approve Reservation #{{ $reservation->id }}?</p>
                    <p class="text-slate-500">Are you sure you want to approve this vehicle reservation request?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="approveReservation({{ $reservation->id }})">Approve</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- END: Approve Modal -->

<!-- BEGIN: Decline Modal -->
@foreach($reservations as $reservation)
<div id="decline-modal-{{ $reservation->id }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Decline Reservation</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <p class="text-lg font-medium mb-2">Decline Reservation #{{ $reservation->id }}?</p>
                    <p class="text-slate-500">Are you sure you want to decline this vehicle reservation request?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="declineReservation({{ $reservation->id }})">Decline</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- END: Decline Modal -->

<!-- BEGIN: View Details Modal -->
<div id="view-details-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Reservation Details</h2>
                <button class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Close
                </button>
            </div>
            <div class="modal-body">
                <div id="details-loading" class="text-center py-8">
                    <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150 cursor-not-allowed">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <polyline class="opacity-75" points="20,6 12,14 6,20"></polyline>
                        </svg>
                        Loading reservation details...
                    </div>
                </div>
                
                <div id="details-content" class="hidden">
                    <div class="intro-y box overflow-hidden">
                        <div class="flex flex-col lg:flex-row pt-10 px-10 sm:px-10 sm:pt-10 lg:pb-10 text-center sm:text-left">
                            <div>
                                <div class="font-semibold text-primary text-3xl">RESERVATION</div>
                                <div class="mt-4" id="details-qrcode">
                                     <div class="w-32 h-32 mx-auto">QR Code</div>
                                 </div>
                            </div>
                            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
                                <div class="text-xl text-primary font-medium" id="details-vehicle-name">Vehicle Name</div>
                                <div class="mt-1" id="details-plate-number">Plate Number</div>
                                <div class="mt-1" id="details-reservation-type">Reservation Type</div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col lg:flex-row border-b px-5 sm:px-10 pt-0 pb-10 sm:pb-20 text-center sm:text-left">
                            <div>
                                <div class="text-base text-slate-500">Reservation Details</div>
                                <div class="text-lg font-medium text-primary mt-2" id="details-destination">Destination</div>
                                <div class="mt-1">Driver: <span id="details-driver">Driver Name</span></div>
                                <div class="mt-1">Requested By: <span id="details-requested-by">User Name</span></div>
                            </div>
                            <div class="mt-10 lg:mt-0 lg:ml-auto lg:text-right">
                                <div class="text-base text-slate-500">Reservation ID</div>
                                <div class="text-lg text-primary font-medium mt-2" id="details-reservation-id">#0000</div>
                                <div class="mt-1" id="details-status">Status</div>
                            </div>
                        </div>
                        
                        <div class="px-5 sm:px-16 py-10 sm:py-20">
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">DESCRIPTION</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-right whitespace-nowrap">DETAILS</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-right whitespace-nowrap">DATE & TIME</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-right whitespace-nowrap">STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border-b dark:border-darkmode-400">
                                                <div class="font-medium whitespace-nowrap">Start Date & Time</div>
                                                <div class="text-slate-500 text-sm mt-0.5 whitespace-nowrap">Reservation begins</div>
                                            </td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32">-</td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32" id="details-start-datetime">Start Time</td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32 font-medium" id="details-start-status">Scheduled</td>
                                        </tr>
                                        <tr>
                                            <td class="border-b dark:border-darkmode-400">
                                                <div class="font-medium whitespace-nowrap">End Date & Time</div>
                                                <div class="text-slate-500 text-sm mt-0.5 whitespace-nowrap">Reservation ends</div>
                                            </td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32">-</td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32" id="details-end-datetime">End Time</td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32 font-medium" id="details-end-status">Scheduled</td>
                                        </tr>
                                        <tr>
                                            <td class="border-b dark:border-darkmode-400">
                                                <div class="font-medium whitespace-nowrap">Purpose/Reason</div>
                                                <div class="text-slate-500 text-sm mt-0.5 whitespace-nowrap">Trip purpose</div>
                                            </td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32" id="details-reason-details">Reason</td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32" id="details-reason">Reason</td>
                                            <td class="text-right border-b dark:border-darkmode-400 w-32 font-medium">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="font-medium whitespace-nowrap">Location Coordinates</div>
                                                <div class="text-slate-500 text-sm mt-0.5 whitespace-nowrap">GPS coordinates</div>
                                            </td>
                                            <td class="text-right w-32">-</td>
                                            <td class="text-right w-32">
                                                <div id="details-longitude">Longitude</div>
                                                <div id="details-latitude">Latitude</div>
                                            </td>
                                            <td class="text-right w-32 font-medium">Set</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="px-5 sm:px-16 py-0 sm:py-0">
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">PASSENGER NAME</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">ROLE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="details-passengers-table">
                                        <tr>
                                            <td class="border-b dark:border-darkmode-400 text-center py-4" colspan="3">
                                                <div class="text-slate-500">No passengers assigned</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END: View Details Modal -->

@endsection

@push('scripts')
    <script src="{{ asset('js/vehicle-management/list-request-reserve.js') }}"></script>
@endpush
