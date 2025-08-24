@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Profile Layout
                        </h2>
                    </div>
                    <div class="intro-y box px-5 pt-5 mt-5">
                        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                                    <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                    <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-primary rounded-full p-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="camera" class="lucide lucide-camera w-4 h-4 text-white" data-lucide="camera"><path d="M14.5 4h-5L7 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-3l-2.5-3z"></path><circle cx="12" cy="13" r="3"></circle></svg> </div>
                                </div>
                                <div class="ml-5">
                                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Angelina Jolie</div>
                                    <div class="text-slate-500">Software Engineer</div>
                                </div>
                            </div>
                            <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                                <div class="font-medium text-center lg:text-left lg:mt-3">Contact Details</div>
                                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                                    <div class="truncate sm:whitespace-normal flex items-center"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="mail" data-lucide="mail" class="lucide lucide-mail w-4 h-4 mr-2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> angelinajolie@left4code.com </div>
                                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="instagram" data-lucide="instagram" class="lucide lucide-instagram w-4 h-4 mr-2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg> Instagram Angelina Jolie </div>
                                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="twitter" data-lucide="twitter" class="lucide lucide-twitter w-4 h-4 mr-2"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5 0-.28-.03-.56-.08-.83A7.72 7.72 0 0023 3z"></path></svg> Twitter Angelina Jolie </div>
                                </div>
                            </div>
                            <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0">
                                <div class="font-medium text-center lg:text-left lg:mt-5">Sales Growth</div>
                                <div class="flex items-center justify-center lg:justify-start mt-2">
                                    <div class="mr-2 w-20 flex"> USP: <span class="ml-3 font-medium text-success">+23%</span> </div>
                                    <div class="w-3/4">
                                        <div class="h-[55px]">
                                            <canvas class="simple-line-chart-1 -mr-5" width="213" height="55" style="display: block; box-sizing: border-box; height: 55px; width: 213px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-center lg:justify-start">
                                    <div class="mr-2 w-20 flex"> STP: <span class="ml-3 font-medium text-danger">-2%</span> </div>
                                    <div class="w-3/4">
                                        <div class="h-[55px]">
                                            <canvas class="simple-line-chart-2 -mr-5" width="213" height="55" style="display: block; box-sizing: border-box; height: 55px; width: 213px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
                            <li id="dashboard-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4 active" data-tw-target="#dashboard" aria-controls="dashboard" aria-selected="true" role="tab"> Dashboard </a> </li>
                            <li id="account-and-profile-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4" data-tw-target="#account-and-profile" aria-selected="false" role="tab"> Account &amp; Profile </a> </li>
                            <li id="activities-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4" data-tw-target="#activities" aria-selected="false" role="tab"> Activities </a> </li>
                            <li id="tasks-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-4" data-tw-target="#tasks" aria-selected="false" role="tab"> Tasks </a> </li>
                        </ul>
                    </div>
                    <div class="intro-y tab-content mt-5">
                        <div id="dashboard" class="tab-pane active" role="tabpanel" aria-labelledby="dashboard-tab">
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: Top Categories -->
                                <div class="intro-y box col-span-12 lg:col-span-6">
                                    <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Top Categories
                                        </h2>
                                        <div class="dropdown ml-auto">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" data-lucide="plus" class="lucide lucide-plus w-4 h-4 mr-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add Category </a>
                                                    </li>
                                                    <li>
                                                        <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="settings" data-lucide="settings" class="lucide lucide-settings w-4 h-4 mr-2"><path d="M12.22 2h-.44a2 2 0 00-2 2v.18a2 2 0 01-1 1.73l-.43.25a2 2 0 01-2 0l-.15-.08a2 2 0 00-2.73.73l-.22.38a2 2 0 00.73 2.73l.15.1a2 2 0 011 1.72v.51a2 2 0 01-1 1.74l-.15.09a2 2 0 00-.73 2.73l.22.38a2 2 0 002.73.73l.15-.08a2 2 0 012 0l.43.25a2 2 0 011 1.73V20a2 2 0 002 2h.44a2 2 0 002-2v-.18a2 2 0 011-1.73l.43-.25a2 2 0 012 0l.15.08a2 2 0 002.73-.73l.22-.39a2 2 0 00-.73-2.73l-.15-.08a2 2 0 01-1-1.74v-.5a2 2 0 011-1.74l.15-.09a2 2 0 00.73-2.73l-.22-.38a2 2 0 00-2.73-.73l-.15.08a2 2 0 01-2 0l-.43-.25a2 2 0 01-1-1.73V4a2 2 0 00-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg> Settings </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex flex-col sm:flex-row">
                                            <div class="mr-auto">
                                                <a href="" class="font-medium">Wordpress Template</a> 
                                                <div class="text-slate-500 mt-1">HTML, PHP, Mysql</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-32 -ml-2 sm:ml-0 mt-5 mr-auto sm:mr-5">
                                                    <div class="h-[30px]">
                                                        <canvas class="simple-line-chart-1" data-random="true" width="96" height="30" style="display: block; box-sizing: border-box; height: 30px; width: 96px;"></canvas>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-medium">6.5k</div>
                                                    <div class="bg-success/20 text-success rounded px-2 mt-1.5">+150</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row mt-5">
                                            <div class="mr-auto">
                                                <a href="" class="font-medium">Bootstrap HTML Template</a> 
                                                <div class="text-slate-500 mt-1">HTML, PHP, Mysql</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-32 -ml-2 sm:ml-0 mt-5 mr-auto sm:mr-5">
                                                    <div class="h-[30px]">
                                                        <canvas class="simple-line-chart-1" data-random="true" width="96" height="30" style="display: block; box-sizing: border-box; height: 30px; width: 96px;"></canvas>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-medium">2.5k</div>
                                                    <div class="bg-pending/10 text-pending rounded px-2 mt-1.5">+150</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row mt-5">
                                            <div class="mr-auto">
                                                <a href="" class="font-medium">Tailwind HTML Template</a> 
                                                <div class="text-slate-500 mt-1">HTML, PHP, Mysql</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-32 -ml-2 sm:ml-0 mt-5 mr-auto sm:mr-5">
                                                    <div class="h-[30px]">
                                                        <canvas class="simple-line-chart-1" data-random="true" width="96" height="30" style="display: block; box-sizing: border-box; height: 30px; width: 96px;"></canvas>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-medium">3.4k</div>
                                                    <div class="bg-primary/10 text-primary rounded px-2 mt-1.5">+150</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Top Categories -->
                                <!-- BEGIN: Work In Progress -->
                                <div class="intro-y box col-span-12 lg:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Work In Progress
                                        </h2>
                                        <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                            <div class="nav nav-tabs dropdown-menu w-40" role="tablist">
                                                <ul class="dropdown-content">
                                                    <li> <a id="work-in-progress-mobile-new-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#work-in-progress-new" class="dropdown-item" role="tab" aria-controls="work-in-progress-new" aria-selected="true">New</a> </li>
                                                    <li> <a id="work-in-progress-mobile-last-week-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#work-in-progress-last-week" class="dropdown-item" role="tab" aria-selected="false">Last Week</a> </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav nav-link-tabs w-auto ml-auto hidden sm:flex" role="tablist">
                                            <li id="work-in-progress-new-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5 active" data-tw-target="#work-in-progress-new" aria-controls="work-in-progress-new" aria-selected="true" role="tab"> New </a> </li>
                                            <li id="work-in-progress-last-week-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5" data-tw-target="#work-in-progress-last-week" aria-selected="false" role="tab"> Last Week </a> </li>
                                        </ul>
                                    </div>
                                    <div class="p-5">
                                        <div class="tab-content">
                                            <div id="work-in-progress-new" class="tab-pane active" role="tabpanel" aria-labelledby="work-in-progress-new-tab">
                                                <div>
                                                    <div class="flex">
                                                        <div class="mr-auto">Pending Tasks</div>
                                                        <div>20%</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-1/2 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="flex">
                                                        <div class="mr-auto">Completed Tasks</div>
                                                        <div>2 / 20</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-1/4 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="flex">
                                                        <div class="mr-auto">Tasks In Progress</div>
                                                        <div>42</div>
                                                    </div>
                                                    <div class="progress h-1 mt-2">
                                                        <div class="progress-bar w-3/4 bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <a href="" class="btn btn-secondary block w-40 mx-auto mt-5">View More Details</a> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Work In Progress -->
                                <!-- BEGIN: Daily Sales -->
                                <div class="intro-y box col-span-12 lg:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Daily Sales
                                        </h2>
                                        <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file" data-lucide="file" class="lucide lucide-file w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg> Download Excel </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-secondary hidden sm:flex"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file" data-lucide="file" class="lucide lucide-file w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg> Download Excel </button>
                                    </div>
                                    <div class="p-5">
                                        <div class="relative flex items-center">
                                            <div class="w-12 h-12 flex-none image-fit">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                            </div>
                                            <div class="ml-4 mr-auto">
                                                <a href="" class="font-medium">Angelina Jolie</a> 
                                                <div class="text-slate-500 mr-5 sm:mr-5">Bootstrap 4 HTML Admin Template</div>
                                            </div>
                                            <div class="font-medium text-slate-600 dark:text-slate-500">+$19</div>
                                        </div>
                                        <div class="relative flex items-center mt-5">
                                            <div class="w-12 h-12 flex-none image-fit">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-8.jpg">
                                            </div>
                                            <div class="ml-4 mr-auto">
                                                <a href="" class="font-medium">Russell Crowe</a> 
                                                <div class="text-slate-500 mr-5 sm:mr-5">Tailwind HTML Admin Template</div>
                                            </div>
                                            <div class="font-medium text-slate-600 dark:text-slate-500">+$25</div>
                                        </div>
                                        <div class="relative flex items-center mt-5">
                                            <div class="w-12 h-12 flex-none image-fit">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-12.jpg">
                                            </div>
                                            <div class="ml-4 mr-auto">
                                                <a href="" class="font-medium">Al Pacino</a> 
                                                <div class="text-slate-500 mr-5 sm:mr-5">Vuejs HTML Admin Template</div>
                                            </div>
                                            <div class="font-medium text-slate-600 dark:text-slate-500">+$21</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Daily Sales -->
                                <!-- BEGIN: Latest Tasks -->
                                <div class="intro-y box col-span-12 lg:col-span-6">
                                    <div class="flex items-center px-5 py-5 sm:py-0 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Latest Tasks
                                        </h2>
                                        <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                            <div class="nav nav-tabs dropdown-menu w-40" role="tablist">
                                                <ul class="dropdown-content">
                                                    <li> <a id="latest-tasks-mobile-new-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#latest-tasks-new" class="dropdown-item" role="tab" aria-controls="latest-tasks-new" aria-selected="true">New</a> </li>
                                                    <li> <a id="latest-tasks-mobile-last-week-tab" href="javascript:;" data-tw-toggle="tab" data-tw-target="#latest-tasks-last-week" class="dropdown-item" role="tab" aria-selected="false">Last Week</a> </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav nav-link-tabs w-auto ml-auto hidden sm:flex" role="tablist">
                                            <li id="latest-tasks-new-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5 active" data-tw-target="#latest-tasks-new" aria-controls="latest-tasks-new" aria-selected="true" role="tab"> New </a> </li>
                                            <li id="latest-tasks-last-week-tab" class="nav-item" role="presentation"> <a href="javascript:;" class="nav-link py-5" data-tw-target="#latest-tasks-last-week" aria-selected="false" role="tab"> Last Week </a> </li>
                                        </ul>
                                    </div>
                                    <div class="p-5">
                                        <div class="tab-content">
                                            <div id="latest-tasks-new" class="tab-pane active" role="tabpanel" aria-labelledby="latest-tasks-new-tab">
                                                <div class="flex items-center">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Create New Campaign</a> 
                                                        <div class="text-slate-500">10:00 AM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                                <div class="flex items-center mt-5">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Meeting With Client</a> 
                                                        <div class="text-slate-500">02:00 PM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                                <div class="flex items-center mt-5">
                                                    <div class="border-l-2 border-primary dark:border-primary pl-4">
                                                        <a href="" class="font-medium">Create New Repository</a> 
                                                        <div class="text-slate-500">04:00 PM</div>
                                                    </div>
                                                    <div class="form-check form-switch ml-auto">
                                                        <input class="form-check-input" type="checkbox">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Latest Tasks -->
                                <!-- BEGIN: General Statistic -->
                                <div class="intro-y box col-span-12">
                                    <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            General Statistics
                                        </h2>
                                        <div class="dropdown ml-auto sm:hidden">
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                            <div class="dropdown-menu w-40">
                                                <ul class="dropdown-content">
                                                    <li>
                                                        <a href="javascript:;" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file" data-lucide="file" class="lucide lucide-file w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg> Download XML </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-secondary hidden sm:flex"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file" data-lucide="file" class="lucide lucide-file w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg> Download XML </button>
                                    </div>
                                    <div class="grid grid-cols-1 2xl:grid-cols-7 gap-6 p-5">
                                        <div class="2xl:col-span-2">
                                            <div class="grid grid-cols-2 gap-6">
                                                <div class="col-span-2 sm:col-span-1 2xl:col-span-2 box dark:bg-darkmode-500 p-5">
                                                    <div class="font-medium">Net Worth</div>
                                                    <div class="flex items-center mt-1 sm:mt-0">
                                                        <div class="mr-4 w-20 flex"> USP: <span class="ml-3 font-medium text-success">+23%</span> </div>
                                                        <div class="w-5/6 overflow-auto">
                                                            <div class="h-[51px]">
                                                                <canvas class="simple-line-chart-1" data-random="true" width="308" height="51" style="display: block; box-sizing: border-box; height: 51px; width: 308px;"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-2 sm:col-span-1 2xl:col-span-2 box dark:bg-darkmode-500 p-5">
                                                    <div class="font-medium">Sales</div>
                                                    <div class="flex items-center mt-1 sm:mt-0">
                                                        <div class="mr-4 w-20 flex"> USP: <span class="ml-3 font-medium text-danger">-5%</span> </div>
                                                        <div class="w-5/6 overflow-auto">
                                                            <div class="h-[51px]">
                                                                <canvas class="simple-line-chart-1" data-random="true" width="308" height="51" style="display: block; box-sizing: border-box; height: 51px; width: 308px;"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-2 sm:col-span-1 2xl:col-span-2 box dark:bg-darkmode-500 p-5">
                                                    <div class="font-medium">Profit</div>
                                                    <div class="flex items-center mt-1 sm:mt-0">
                                                        <div class="mr-4 w-20 flex"> USP: <span class="ml-3 font-medium text-danger">-10%</span> </div>
                                                        <div class="w-5/6 overflow-auto">
                                                            <div class="h-[51px]">
                                                                <canvas class="simple-line-chart-1" data-random="true" width="308" height="51" style="display: block; box-sizing: border-box; height: 51px; width: 308px;"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-2 sm:col-span-1 2xl:col-span-2 box dark:bg-darkmode-500 p-5">
                                                    <div class="font-medium">Products</div>
                                                    <div class="flex items-center mt-1 sm:mt-0">
                                                        <div class="mr-4 w-20 flex"> USP: <span class="ml-3 font-medium text-success">+55%</span> </div>
                                                        <div class="w-5/6 overflow-auto">
                                                            <div class="h-[51px]">
                                                                <canvas class="simple-line-chart-1" data-random="true" width="308" height="51" style="display: block; box-sizing: border-box; height: 51px; width: 308px;"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="2xl:col-span-5 w-full">
                                            <div class="flex justify-center mt-8">
                                                <div class="flex items-center mr-5">
                                                    <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                                                    <span>Product Profit</span> 
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="w-2 h-2 bg-slate-300 rounded-full mr-3"></div>
                                                    <span>Author Sales</span> 
                                                </div>
                                            </div>
                                            <div class="mt-8">
                                                <div class="h-[420px]">
                                                    <canvas id="stacked-bar-chart-1" width="836" height="420" style="display: block; box-sizing: border-box; height: 420px; width: 836px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: General Statistic -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/profile-management/profile-management.js') }}"></script>
@endpush