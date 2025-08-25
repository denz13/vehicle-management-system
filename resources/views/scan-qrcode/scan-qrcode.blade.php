@extends('layout._partials.master')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Schedule Scanner
        </h2>
    </div>

    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Scanner Side Menu -->
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
            <div class="intro-y pr-1">
                <div class="box p-2">
                    <ul class="nav nav-pills" role="tablist">
                        <li id="scanner-tab" class="nav-item flex-1" role="presentation">
                            <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#scanner"
                                type="button" role="tab" aria-controls="scanner" aria-selected="true">Scanner</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div id="scanner" class="tab-pane active" role="tabpanel" aria-labelledby="scanner-tab">
                    <!-- Device Scanner -->
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y mt-5">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-barcode report-box__icon text-primary">
                                        <path d="M3 5v14" />
                                        <path d="M8 5v14" />
                                        <path d="M12 5v14" />
                                        <path d="M17 5v14" />
                                        <path d="M21 5v14" />
                                    </svg>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    <input id="deviceScanner" type="text" class="form-control w-full" 
                                        placeholder="Scan schedule QR code" autofocus>
                                </div>
                                <div class="text-base text-slate-500 mt-1">Device Scanner</div>
                            </div>
                        </div>
                    </div>

                    <!-- Camera Scanner -->
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y mt-5">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-qr-code report-box__icon text-primary">
                                        <rect width="5" height="5" x="3" y="3" rx="1" />
                                        <rect width="5" height="5" x="16" y="3" rx="1" />
                                        <rect width="5" height="5" x="3" y="16" rx="1" />
                                        <path d="M21 16h-3a2 2 0 0 0-2 2v3" />
                                        <path d="M21 21v.01" />
                                        <path d="M12 7v3a2 2 0 0 1-2 2H7" />
                                        <path d="M3 12h.01" />
                                        <path d="M12 3h.01" />
                                        <path d="M12 16v.01" />
                                        <path d="M16 12h1" />
                                        <path d="M21 12v.01" />
                                        <path d="M12 21v-1" />
                                    </svg>
                                    <div class="ml-auto">
                                        <button id="openQrScanner" class="btn btn-primary">Open Camera</button>
                                    </div>
                                </div>
                                <div id="result" class="text-base text-slate-500 mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Scanner Side Menu -->

        <!-- BEGIN: Schedule Content -->
        <div class="intro-y col-span-12 lg:col-span-8 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-5 mt-5">
                @forelse($schedules as $schedule)
                    @php
                        // Get the latest scan record (most recent by logtime)
                        $scanRecord = $schedule->scanRecords()->orderBy('logtime', 'desc')->first();
                        $scanStatus = $scanRecord ? $scanRecord->workstate : null;
                        $scanTime = $scanRecord ? $scanRecord->logtime : null;
                    @endphp
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="box">
                            <div class="p-5">
                                <div class="h-40 2xl:h-56 image-fit rounded-md overflow-hidden before:block before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-10 before:bg-gradient-to-t before:from-black before:to-black/10">
                                    @if($schedule->user && $schedule->user->photo_url)
                                        <img alt="Profile Image" class="rounded-md object-cover w-full h-full" src="{{ $schedule->user->photo_url }}">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-0 text-white px-5 pb-6 z-10">
                                        <span class="text-white/90 text-xs mt-3 block">Requested by: {{ $schedule->user ? $schedule->user->name : 'Unknown User' }}</span>
                                    </div>
                                </div>
                                <div class="text-slate-600 dark:text-slate-500 mt-5">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash w-4 h-4 mr-2">
                                            <line x1="4" y1="9" x2="20" y2="9"></line>
                                            <line x1="4" y1="15" x2="20" y2="15"></line>
                                            <line x1="10" y1="3" x2="8" y2="21"></line>
                                            <line x1="16" y1="3" x2="14" y2="21"></line>
                                        </svg>
                                        @if($schedule->qrcode)
                                            <span class="ml-1">{{ $schedule->qrcode }}</span>
                                        @else
                                            <span class="ml-1">No QR Code</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4 mr-2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($schedule->start_datetime)->format('M d, Y h:i A') }}
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-4 h-4 mr-2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        @if($schedule->driver_user_id && $schedule->driver)
                                            <span class="ml-1">{{ $schedule->driver->name ?? $schedule->driver }}</span>
                                        @elseif($schedule->driver)
                                            <span class="ml-1">{{ $schedule->driver }}</span>
                                        @else
                                            <span class="ml-1">No Driver Assigned</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck w-4 h-4 mr-2">
                                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                            <circle cx="9" cy="10" r="2"></circle>
                                            <path d="m8 14 6-6"></path>
                                            <path d="m16 14 6-6"></path>
                                        </svg>
                                        @if($schedule->vehicle)
                                            <span class="ml-1">{{ $schedule->vehicle->plate_number ?? 'Unknown Vehicle' }}</span>
                                        @else
                                            <span class="ml-1">No Vehicle Assigned</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 mr-2">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        @if($schedule->destination)
                                            <span class="ml-1">{{ $schedule->destination }}</span>
                                        @else
                                            <span class="ml-1">No Destination</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4 mr-2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        @if($scanStatus == 0 || $scanStatus == '0')
                                            <span class="text-warning ml-2">Departed</span>
                                        @elseif($scanStatus == 1 || $scanStatus == '1')
                                            <span class="text-success ml-2">Arrived</span>
                                        @else
                                            <span class="text-slate-500 ml-2">Pending ({{ $scanStatus }})</span>
                                        @endif
                                    </div>
                                    @if($scanTime)
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 mr-2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        <span class="ml-1 text-xs text-slate-500">
                                            Scanned: {{ \Carbon\Carbon::parse($scanTime)->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-center lg:justify-end items-center p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                <a class="flex items-center text-primary show-qr" href="javascript:;" data-schedule-id="{{ $schedule->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-4 h-4 mr-1">
                                        <rect width="5" height="5" x="3" y="3" rx="1"/>
                                        <rect width="5" height="5" x="16" y="3" rx="1"/>
                                        <rect width="5" height="5" x="3" y="16" rx="1"/>
                                        <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                                        <path d="M21 21v.01"/>
                                        <path d="M12 7v3a2 2 0 0 1-2 2H7"/>
                                        <path d="M3 12h.01"/>
                                        <path d="M12 3h.01"/>
                                        <path d="M12 16v.01"/>
                                        <path d="M16 12h1"/>
                                        <path d="M21 12v.01"/>
                                        <path d="M12 21v-1"/>
                                    </svg>
                                    Show QR
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-12 text-center">
                        <div class="text-slate-500">No scanned schedules found</div>
                        <div class="text-slate-400 text-sm mt-2">Scan a QR code to see schedules here</div>
                    </div>
                @endforelse
            </div>

            <!-- BEGIN: Pagination -->
            <div class="intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                {{ $schedules->links() }}
            </div>
            <!-- END: Pagination -->
        </div>
        <!-- END: Schedule Content -->
    </div>

    <!-- BEGIN: QR Scanner Modal -->
    <div id="qrScannerModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Scan QR Code</h2>
                </div>
                <div class="modal-body p-0">
                    <div class="p-5">
                        <div id="reader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: QR Scanner Modal -->

    <!-- BEGIN: Departure/Arrival Modal -->
    <div id="departureArrivalModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Schedule Details</h2>
                </div>
                <div class="modal-body">
                    <div id="schedule-info"></div>
                    <div class="flex justify-center gap-2 mt-5">
                        <button id="btnDeparture" class="btn btn-primary">Mark Departure</button>
                        <button id="btnArrival" class="btn btn-success">Mark Arrival</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Departure/Arrival Modal -->

    <!-- BEGIN: QR Code Modal -->
    <div id="qrCodeModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Schedule QR Code</h2>
                </div>
                <div class="modal-body p-0">
                    <div class="p-5 flex flex-col items-center justify-center">
                        <div id="qrcode-display" class="flex justify-center items-center"></div>
                        <div id="schedule-details" class="mt-4 text-center"></div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Close</button>
                        <button type="button" class="btn btn-primary w-24" id="download-qr">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: QR Code Modal -->

    <!-- BEGIN: Success Modal -->
    <div id="success-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="check-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Success!</div>
                        <div class="text-slate-500 mt-2" id="success-message">Operation completed successfully!</div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Success Modal -->

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="QR code scanned successfully" 
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
    message="An error occurred while scanning" 
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
    message="QR code scanner is ready" 
    :showButton="false" 
    :autoHide="true" 
    :duration="4000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="departure_success" 
    type="success" 
    title="Success!" 
    message="Departure marked successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="arrival_success" 
    type="success" 
    title="Success!" 
    message="Arrival marked successfully" 
    :showButton="false" 
    :autoHide="true" 
    :duration="3000" 
    position="right" 
    gravity="top" 
/>

<x-notification-toast 
    id="schedule_not_found" 
    type="error" 
    title="Schedule Not Found!" 
    message="Approved schedule not found. Only approved schedules can be scanned." 
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
    message="Please check the input for errors" 
    :showButton="false" 
    :autoHide="true" 
    :duration="5000" 
    position="right" 
    gravity="top" 
/>
<!-- END: Notification Toasts -->

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script src="{{ asset('js/scan-qrcode/scan-qrcode.js') }}"></script>
@endpush