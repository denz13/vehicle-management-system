@extends('layout._partials.master')

@section('content')
<div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 2xl:col-span-9">
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: General Report -->
                                <div class="col-span-12 mt-8">
                                    <div class="intro-y flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            General Report
                                        </h2>
                                        <a href="" class="ml-auto flex items-center text-primary"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="refresh-ccw" data-lucide="refresh-ccw" class="lucide lucide-refresh-ccw w-4 h-4 mr-3"><path d="M3 2v6h6"></path><path d="M21 12A9 9 0 006 5.3L3 8"></path><path d="M21 22v-6h-6"></path><path d="M3 12a9 9 0 0015 6.7l3-2.7"></path></svg> Reload Data </a>
                                    </div>
                                    <div class="grid grid-cols-12 gap-6 mt-5">
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="users" data-lucide="users" class="lucide lucide-users report-box__icon text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="m22 21-2-2a4 4 0 0 0-4-4H12a4 4 0 0 0-4 4v2"></path><circle cx="16" cy="7" r="4"></circle></svg> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer"> {{ $stats['users']['change'] }}% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($stats['users']['total']) }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Total Users</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle report-box__icon text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer"> {{ $stats['approved_reservations']['change'] }}% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($stats['approved_reservations']['total']) }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Approved Reservations</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="truck" data-lucide="truck" class="lucide lucide-truck report-box__icon text-warning"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16,8 20,8 23,11 23,16 16,16 16,8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer"> {{ $stats['vehicles']['change'] }}% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($stats['vehicles']['total']) }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Total Vehicles</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text report-box__icon text-info"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer"> {{ $stats['posts']['change'] }}% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($stats['posts']['total']) }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Total Posts</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: General Report -->
                                
                                <!-- BEGIN: Additional Statistics -->
                                <div class="col-span-12 mt-6">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x-circle" data-lucide="x-circle" class="lucide lucide-x-circle report-box__icon text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-danger tooltip cursor-pointer"> {{ $stats['declined_reservations']['change'] }}% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-down" class="lucide lucide-chevron-down w-4 h-4 ml-0.5"><polyline points="6 9 12 15 18 9"></polyline></svg> </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($stats['declined_reservations']['total']) }}</div>
                                                    <div class="text-base text-slate-500 mt-1">Declined Reservations</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="qr-code" data-lucide="qr-code" class="lucide lucide-qr-code report-box__icon text-primary"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><path d="M10 10h4v4h-4z"></path><path d="M10 14h4v4h-4z"></path><path d="M14 10h4v4h-4z"></path><path d="M14 14h4v4h-4z"></path></svg> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer"> {{ $stats['qr_scans']['change'] }}% <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-up" class="lucide lucide-chevron-up w-4 h-4 ml-0.5"><polyline points="18 15 12 9 6 15"></polyline></svg> </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($stats['qr_scans']['total']) }}</div>
                                                    <div class="text-base text-slate-500 mt-1">QR Code Scans</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Additional Statistics -->
                                
                                <!-- BEGIN: Weekly Top Products -->
                                <div class="col-span-12 mt-6">
                                    <div class="intro-y block sm:flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Recent Activities
                                        </h2>
                                        
                                    </div>
                                    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                        <table class="table table-report sm:mt-2">
                                            <thead>
                                                <tr>
                                                    <th class="whitespace-nowrap">TYPE</th>
                                                    <th class="whitespace-nowrap">DESCRIPTION</th>
                                                    <th class="text-center whitespace-nowrap">STATUS</th>
                                                    <th class="text-center whitespace-nowrap">DATE</th>
                                                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="intro-x">
                                                    <td class="w-40">
                                                        <div class="flex items-center">
                                                            <div class="w-10 h-10 bg-primary/10 text-primary flex items-center justify-center rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="m22 21-2-2a4 4 0 0 0-4-4H12a4 4 0 0 0-4 4v2"></path><circle cx="16" cy="7" r="4"></circle></svg>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="" class="font-medium whitespace-nowrap">New User Registration</a> 
                                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">User Management</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="flex items-center justify-center text-success"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active </div>
                                                    </td>
                                                    <td class="text-center">{{ now()->format('M d, Y') }}</td>
                                                    <td class="table-report__action w-56">
                                                        <div class="flex justify-center items-center">
                                                            <a class="flex items-center mr-3" href="{{ route('profile-management') }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" data-lucide="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="intro-x">
                                                    <td class="w-40">
                                                        <div class="flex items-center">
                                                            <div class="w-10 h-10 bg-warning/10 text-warning flex items-center justify-center rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16,8 20,8 23,11 23,16 16,16 16,8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="" class="font-medium whitespace-nowrap">Vehicle Reservation</a> 
                                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Vehicle Management</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="flex items-center justify-center text-success"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Approved </div>
                                                    </td>
                                                    <td class="text-center">{{ now()->format('M d, Y') }}</td>
                                                    <td class="table-report__action w-56">
                                                        <div class="flex justify-center items-center">
                                                            <a class="flex items-center mr-3" href="{{ route('vehicle-management') }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" data-lucide="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="intro-x">
                                                    <td class="w-40">
                                                        <div class="flex items-center">
                                                            <div class="w-10 h-10 bg-info/10 text-info flex items-center justify-center rounded-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="" class="font-medium whitespace-nowrap">New Post Created</a> 
                                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Post Management</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="flex items-center justify-center text-success"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Active </div>
                                                    </td>
                                                    <td class="text-center">{{ now()->format('M d, Y') }}</td>
                                                    <td class="table-report__action w-56">
                                                        <div class="flex justify-center items-center">
                                                            <a class="flex items-center mr-3" href="{{ route('post') }}"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" data-lucide="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END: Weekly Top Products -->
                            </div>
                        </div>
                        <div class="col-span-12 2xl:col-span-3">
                            <div class="2xl:border-l -mb-10 pb-10">
                                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                                    <!-- BEGIN: Quick Actions -->
                                    <div class="col-span-12 md:col-span-6 xl:col-span-4 2xl:col-span-12 mt-3 2xl:mt-8">
                                        <div class="intro-x flex items-center h-10">
                                            <h2 class="text-lg font-medium truncate mr-5">
                                                Quick Actions
                                            </h2>
                                        </div>
                                        <div class="mt-5">
                                            <div class="intro-x">
                                                <a href="{{ route('post') }}" class="box px-5 py-3 mb-3 flex items-center zoom-in hover:bg-slate-50 dark:hover:bg-darkmode-400">
                                                    <div class="w-10 h-10 flex-none bg-primary/10 text-primary flex items-center justify-center rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    </div>
                                                    <div class="ml-4 mr-auto">
                                                        <div class="font-medium">Create Post</div>
                                                        <div class="text-slate-500 text-xs mt-0.5">Add new announcement</div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="intro-x">
                                                <a href="{{ route('vehicle-management') }}" class="box px-5 py-3 mb-3 flex items-center zoom-in hover:bg-slate-50 dark:hover:bg-darkmode-400">
                                                    <div class="w-10 h-10 flex-none bg-warning/10 text-warning flex items-center justify-center rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16,8 20,8 23,11 23,16 16,16 16,8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                                    </div>
                                                    <div class="ml-4 mr-auto">
                                                        <div class="font-medium">Manage Vehicles</div>
                                                        <div class="text-slate-500 text-xs mt-0.5">Vehicle operations</div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="intro-x">
                                                <a href="{{ route('chat') }}" class="box px-5 py-3 mb-3 flex items-center zoom-in hover:bg-slate-50 dark:hover:bg-darkmode-400">
                                                    <div class="w-10 h-10 flex-none bg-success/10 text-success flex items-center justify-center rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                    </div>
                                                    <div class="ml-4 mr-auto">
                                                        <div class="font-medium">Chat</div>
                                                        <div class="text-slate-500 text-xs mt-0.5">Team communication</div>
                                                    </div>
                                                </a>
                                                </div>
                                            </div>
                                                    </div>
                                    <!-- END: Quick Actions -->
                                    
                                    <!-- BEGIN: Important Notes -->
                                    <div class="col-span-12 md:col-span-6 xl:col-span-12 xl:col-start-1 xl:row-start-1 2xl:col-start-auto 2xl:row-start-auto mt-3">
                                        <div class="intro-x flex items-center h-10">
                                            <h2 class="text-lg font-medium truncate mr-auto">
                                                System Status
                                            </h2>
                                        </div>
                                        <div class="mt-5 intro-x">
                                            <div class="box zoom-in">
                                                <div class="p-5">
                                                    <div class="text-base font-medium truncate">Vehicle Management System</div>
                                                    <div class="text-slate-400 mt-1">All systems operational</div>
                                                    <div class="text-slate-500 text-justify mt-1">Your vehicle management system is running smoothly with {{ $stats['users']['total'] }} active users, {{ $stats['vehicles']['total'] }} vehicles, and {{ $stats['posts']['total'] }} announcements.</div>
                                                        <div class="font-medium flex mt-5">
                                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary py-1 px-2">Refresh</a>
                                                        <a href="{{ route('profile-management') }}" class="btn btn-outline-secondary py-1 px-2 ml-auto">Profile</a>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END: Important Notes -->
                                    
                                </div>
                            </div>
                        </div>
                    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard/dashboard.js') }}"></script>
@endpush