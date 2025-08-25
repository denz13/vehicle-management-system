@extends('layout._partials.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Chat System
    </h2>
</div>
<div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
                        <!-- BEGIN: Chat Side Menu -->
                        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
                            <div class="intro-y pr-1">
                                <div class="box p-2">
                                    <ul class="nav nav-pills" role="tablist">
                                        <li id="chats-tab" class="nav-item flex-1" role="presentation">
                                            <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#chats" type="button" role="tab" aria-controls="chats" aria-selected="true"> Chats </button>
                                        </li>
                                        <li id="friends-tab" class="nav-item flex-1" role="presentation">
                                            <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#friends" type="button" role="tab" aria-controls="friends" aria-selected="false"> Friends </button>
                                        </li>
                                        <li id="profile-tab" class="nav-item flex-1" role="presentation">
                                            <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"> Profile </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div id="chats" class="tab-pane active" role="tabpanel" aria-labelledby="chats-tab">
                                    <div class="pr-1">
                                        <div class="box px-5 pt-5 pb-5 lg:pb-0 mt-5">
                                            <div class="relative text-slate-500">
                                                <input type="text" class="form-control py-3 px-4 border-transparent bg-slate-100 pr-10" placeholder="Search for messages or users...">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 hidden sm:absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
                                            </div>
                                            <div class="overflow-x-auto scrollbar-hidden">
                                                <div class="flex mt-5">
                                                    
                                                    <a href="" class="w-10 mr-4 cursor-pointer">
                                                        <div class="w-10 h-10 flex-none image-fit rounded-full">
                                                            <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-14.jpg">
                                                            <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                                        </div>
                                                        <div class="text-xs text-slate-500 truncate text-center mt-2">Sylvester Stallone</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-4">
                                       
                                        <div class="intro-x cursor-pointer box relative flex items-center p-5 mt-5">
                                            <div class="w-12 h-12 flex-none image-fit mr-1">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-14.jpg">
                                                <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                            </div>
                                            <div class="ml-2 overflow-hidden">
                                                <div class="flex items-center">
                                                    <a href="javascript:;" class="font-medium">Sylvester Stallone</a> 
                                                    <div class="text-xs text-slate-400 ml-auto">06:05 AM</div>
                                                </div>
                                                <div class="w-full truncate text-slate-500 mt-0.5">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</div>
                                            </div>
                                            <div class="w-5 h-5 flex items-center justify-center absolute top-0 right-0 text-xs text-white rounded-full bg-primary font-medium -mt-1 -mr-1">7</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="friends" class="tab-pane" role="tabpanel" aria-labelledby="friends-tab">
                                    
                                    <div class="chat__user-list overflow-y-auto scrollbar-hidden pr-1 pt-1">
                                        <div class="mt-4 text-slate-500">A</div>
                                        <div class="cursor-pointer box relative flex items-center p-5 mt-5">
                                            <div class="w-12 h-12 flex-none image-fit mr-1">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                                <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                            </div>
                                            <div class="ml-2 overflow-hidden">
                                                <div class="flex items-center"> <a href="" class="font-medium">Kevin Spacey</a> </div>
                                                <div class="w-full truncate text-slate-500 mt-0.5">Last seen 2 hours ago</div>
                                            </div>
                                            <div class="dropdown ml-auto">
                                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="share-2" data-lucide="share-2" class="lucide lucide-share-2 w-4 h-4 mr-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share Contact </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="copy" data-lucide="copy" class="lucide lucide-copy w-4 h-4 mr-2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg> Copy Contact </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cursor-pointer box relative flex items-center p-5 mt-5">
                                            <div class="w-12 h-12 flex-none image-fit mr-1">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-15.jpg">
                                                <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                            </div>
                                            <div class="ml-2 overflow-hidden">
                                                <div class="flex items-center"> <a href="" class="font-medium">Robert De Niro</a> </div>
                                                <div class="w-full truncate text-slate-500 mt-0.5">Last seen 2 hours ago</div>
                                            </div>
                                            <div class="dropdown ml-auto">
                                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="share-2" data-lucide="share-2" class="lucide lucide-share-2 w-4 h-4 mr-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share Contact </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="copy" data-lucide="copy" class="lucide lucide-copy w-4 h-4 mr-2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg> Copy Contact </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 text-slate-500">B</div>
                                        <div class="cursor-pointer box relative flex items-center p-5 mt-5">
                                            <div class="w-12 h-12 flex-none image-fit mr-1">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-2.jpg">
                                                <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                            </div>
                                            <div class="ml-2 overflow-hidden">
                                                <div class="flex items-center"> <a href="" class="font-medium">Robert De Niro</a> </div>
                                                <div class="w-full truncate text-slate-500 mt-0.5">Last seen 2 hours ago</div>
                                            </div>
                                            <div class="dropdown ml-auto">
                                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="share-2" data-lucide="share-2" class="lucide lucide-share-2 w-4 h-4 mr-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share Contact </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="copy" data-lucide="copy" class="lucide lucide-copy w-4 h-4 mr-2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg> Copy Contact </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cursor-pointer box relative flex items-center p-5 mt-5">
                                            <div class="w-12 h-12 flex-none image-fit mr-1">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-4.jpg">
                                                <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                            </div>
                                            <div class="ml-2 overflow-hidden">
                                                <div class="flex items-center"> <a href="" class="font-medium">Johnny Depp</a> </div>
                                                <div class="w-full truncate text-slate-500 mt-0.5">Last seen 2 hours ago</div>
                                            </div>
                                            <div class="dropdown ml-auto">
                                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="share-2" data-lucide="share-2" class="lucide lucide-share-2 w-4 h-4 mr-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share Contact </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="copy" data-lucide="copy" class="lucide lucide-copy w-4 h-4 mr-2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg> Copy Contact </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cursor-pointer box relative flex items-center p-5 mt-5">
                                            <div class="w-12 h-12 flex-none image-fit mr-1">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-9.jpg">
                                                <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                                            </div>
                                            <div class="ml-2 overflow-hidden">
                                                <div class="flex items-center"> <a href="" class="font-medium">Sylvester Stallone</a> </div>
                                                <div class="w-full truncate text-slate-500 mt-0.5">Last seen 2 hours ago</div>
                                            </div>
                                            <div class="dropdown ml-auto">
                                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-horizontal" data-lucide="more-horizontal" class="lucide lucide-more-horizontal w-5 h-5 text-slate-500"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="share-2" data-lucide="share-2" class="lucide lucide-share-2 w-4 h-4 mr-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share Contact </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="copy" data-lucide="copy" class="lucide lucide-copy w-4 h-4 mr-2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg> Copy Contact </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="profile" class="tab-pane" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="pr-1">
                                        <!-- Profile content will be populated by JavaScript -->
                                        <div id="profile-content" class="text-center py-10">
                                            <div class="text-slate-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mx-auto mb-4">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <div class="text-lg font-medium">Select a chat to view profile</div>
                                                <div class="text-sm mt-2">Choose someone from the Friends tab or start a conversation</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Dynamic profile content (hidden by default) -->
                                        <div id="dynamic-profile-content" class="hidden">
                                        <div class="box px-5 py-10 mt-5">
                                            <div class="w-20 h-20 flex-none image-fit rounded-full overflow-hidden mx-auto">
                                                    <img id="profile-photo" alt="Profile" src="dist/images/profile-11.jpg">
                                            </div>
                                            <div class="text-center mt-3">
                                                    <div id="profile-name" class="font-medium text-lg">User Name</div>
                                                    <div id="profile-status" class="text-slate-500 mt-1">Online</div>
                                            </div>
                                        </div>
                                        <div class="box p-5 mt-5">
                                            <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
                                                <div>
                                                        <div class="text-slate-500">User ID</div>
                                                        <div id="profile-user-id" class="mt-1">-</div>
                                                </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="hash" data-lucide="hash" class="lucide lucide-hash w-4 h-4 text-slate-500 ml-auto">
                                                        <path d="M4 9h16"></path>
                                                        <path d="M4 15h16"></path>
                                                        <path d="M10 3L8 21"></path>
                                                        <path d="M16 3l2 18"></path>
                                                    </svg>
                                            </div>
                                            <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 py-5">
                                                <div>
                                                        <div class="text-slate-500">Email</div>
                                                        <div id="profile-email" class="mt-1">-</div>
                                                </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="mail" data-lucide="mail" class="lucide lucide-mail w-4 h-4 text-slate-500 ml-auto">
                                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                        <polyline points="22,6 12,13 2,6"></polyline>
                                                    </svg>
                                            </div>
                                            <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 py-5">
                                                <div>
                                                        <div class="text-slate-500">Role</div>
                                                        <div id="profile-role" class="mt-1">-</div>
                                                </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="shield" data-lucide="shield" class="lucide lucide-shield w-4 h-4 text-slate-500 ml-auto">
                                                        <path d="M20 13c0 5-3.5 7.5-8 7.5s-8-2.5-8-7.5c0-5 3.5-7.5 8-7.5s8 2.5 8 7.5z"></path>
                                                        <path d="M9 9l.01 0"></path>
                                                        <path d="M15 9l.01 0"></path>
                                                        <path d="M12 9l.01 0"></path>
                                                        <path d="M12 12l.01 0"></path>
                                                        <path d="M12 15l.01 0"></path>
                                                    </svg>
                                            </div>
                                            <div class="flex items-center pt-5">
                                                <div>
                                                        <div class="text-slate-500">Department</div>
                                                        <div id="profile-department" class="mt-1">-</div>
                                                </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="building" data-lucide="building" class="lucide lucide-building w-4 h-4 text-slate-500 ml-auto">
                                                        <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                                                        <rect x="9" y="9" width="1" height="1"></rect>
                                                        <rect x="14" y="9" width="1" height="1"></rect>
                                                        <rect x="9" y="14" width="1" height="1"></rect>
                                                        <rect x="14" y="14" width="1" height="1"></rect>
                                                        <rect x="9" y="19" width="1" height="1"></rect>
                                                        <rect x="14" y="19" width="1" height="1"></rect>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Chat Side Menu -->
                        <!-- BEGIN: Chat Content -->
                        <div class="intro-y col-span-12 lg:col-span-8 2xl:col-span-9">
                            <div class="chat__box box">
                                <!-- BEGIN: Chat Active -->
                                <div class="hidden h-full flex flex-col">
                                    <div class="flex flex-col sm:flex-row border-b border-slate-200/60 dark:border-darkmode-400 px-5 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 flex-none image-fit relative">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                            </div>
                                            <div class="ml-3 mr-auto">
                                                <div class="font-medium text-base">Kevin Spacey</div>
                                                <div class="text-slate-500 text-xs sm:text-sm">Hey, I am using chat <span class="mx-1">•</span> Online</div>
                                            </div>
                                        </div>
                                        <!-- <div class="flex items-center sm:ml-auto mt-5 sm:mt-0 border-t sm:border-0 border-slate-200/60 pt-3 sm:pt-0 -mx-5 sm:mx-0 px-5 sm:px-0">
                                            <a href="javascript:;" class="w-5 h-5 text-slate-500 ml-5"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="user-plus" data-lucide="user-plus" class="lucide lucide-user-plus w-5 h-5"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> </a>
                                            
                                        </div> -->
                                    </div>
                                    <div class="overflow-y-scroll scrollbar-hidden px-5 pt-5 flex-1">
                                        <div class="chat__box__text-box flex items-end float-left mb-4">
                                            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                            </div>
                                            <div class="bg-slate-100 dark:bg-darkmode-400 px-4 py-3 text-slate-500 rounded-r-md rounded-t-md">
                                                Lorem ipsum sit amen dolor, lorem ipsum sit amen dolor 
                                                <div class="mt-1 text-xs text-slate-500">2 mins ago</div>
                                            </div>
                                            <div class="hidden sm:block dropdown ml-3 my-auto">
                                                <a href="javascript:;" class="dropdown-toggle w-4 h-4 text-slate-500" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="lucide lucide-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="corner-up-left" data-lucide="corner-up-left" class="lucide lucide-corner-up-left w-4 h-4 mr-2"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 00-4-4H4"></path></svg> Reply </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash" data-lucide="trash" class="lucide lucide-trash w-4 h-4 mr-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear-both"></div>
                                        <div class="chat__box__text-box flex items-end float-right mb-4">
                                            <div class="hidden sm:block dropdown mr-3 my-auto">
                                                <a href="javascript:;" class="dropdown-toggle w-4 h-4 text-slate-500" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="lucide lucide-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="corner-up-left" data-lucide="corner-up-left" class="lucide lucide-corner-up-left w-4 h-4 mr-2"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 00-4-4H4"></path></svg> Reply </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash" data-lucide="trash" class="lucide lucide-trash w-4 h-4 mr-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="bg-primary px-4 py-3 text-white rounded-l-md rounded-t-md">
                                                Lorem ipsum sit amen dolor, lorem ipsum sit amen dolor 
                                                <div class="mt-1 text-xs text-white text-opacity-80">1 mins ago</div>
                                            </div>
                                            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative ml-5">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-15.jpg">
                                            </div>
                                        </div>
                                        <div class="clear-both"></div>
                                        <div class="chat__box__text-box flex items-end float-right mb-4">
                                            <div class="hidden sm:block dropdown mr-3 my-auto">
                                                <a href="javascript:;" class="dropdown-toggle w-4 h-4 text-slate-500" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="lucide lucide-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="corner-up-left" data-lucide="corner-up-left" class="lucide lucide-corner-up-left w-4 h-4 mr-2"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 00-4-4H4"></path></svg> Reply </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash" data-lucide="trash" class="lucide lucide-trash w-4 h-4 mr-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="bg-primary px-4 py-3 text-white rounded-l-md rounded-t-md">
                                                Lorem ipsum sit amen dolor, lorem ipsum sit amen dolor 
                                                <div class="mt-1 text-xs text-white text-opacity-80">59 secs ago</div>
                                            </div>
                                            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative ml-5">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-15.jpg">
                                            </div>
                                        </div>
                                        <div class="clear-both"></div>
                                        <div class="chat__box__text-box flex items-end float-left mb-4">
                                            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                            </div>
                                            <div class="bg-slate-100 dark:bg-darkmode-400 px-4 py-3 text-slate-500 rounded-r-md rounded-t-md">
                                                Lorem ipsum sit amen dolor, lorem ipsum sit amen dolor 
                                                <div class="mt-1 text-xs text-slate-500">10 secs ago</div>
                                            </div>
                                            <div class="hidden sm:block dropdown ml-3 my-auto">
                                                <a href="javascript:;" class="dropdown-toggle w-4 h-4 text-slate-500" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="lucide lucide-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="corner-up-left" data-lucide="corner-up-left" class="lucide lucide-corner-up-left w-4 h-4 mr-2"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 00-4-4H4"></path></svg> Reply </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash" data-lucide="trash" class="lucide lucide-trash w-4 h-4 mr-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear-both"></div>
                                        <div class="chat__box__text-box flex items-end float-right mb-4">
                                            <div class="hidden sm:block dropdown mr-3 my-auto">
                                                <a href="javascript:;" class="dropdown-toggle w-4 h-4 text-slate-500" aria-expanded="false" data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="more-vertical" data-lucide="more-vertical" class="lucide lucide-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg> </a>
                                                <div class="dropdown-menu w-40">
                                                    <ul class="dropdown-content">
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="corner-up-left" data-lucide="corner-up-left" class="lucide lucide-corner-up-left w-4 h-4 mr-2"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 00-4-4H4"></path></svg> Reply </a>
                                                        </li>
                                                        <li>
                                                            <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash" data-lucide="trash" class="lucide lucide-trash w-4 h-4 mr-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg> Delete </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="bg-primary px-4 py-3 text-white rounded-l-md rounded-t-md">
                                                Lorem ipsum 
                                                <div class="mt-1 text-xs text-white text-opacity-80">1 secs ago</div>
                                            </div>
                                            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative ml-5">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-15.jpg">
                                            </div>
                                        </div>
                                        <div class="clear-both"></div>
                                        <div class="chat__box__text-box flex items-end float-left mb-4">
                                            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                                                <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-11.jpg">
                                            </div>
                                            <div class="bg-slate-100 dark:bg-darkmode-400 px-4 py-3 text-slate-500 rounded-r-md rounded-t-md">
                                                Kevin Spacey is typing 
                                                <span class="typing-dots ml-1"> <span>.</span> <span>.</span> <span>.</span> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4 pb-10 sm:py-4 flex items-center border-t border-slate-200/60 dark:border-darkmode-400">
                                        <textarea class="chat__box__input form-control dark:bg-darkmode-600 h-16 resize-none border-transparent px-5 py-3 shadow-none focus:border-transparent focus:ring-0" rows="1" placeholder="Type your message..."></textarea>
                                        
                                        <a href="javascript:;" class="w-8 h-8 sm:w-10 sm:h-10 block bg-primary text-white rounded-full flex-none flex items-center justify-center mr-5"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="send" data-lucide="send" class="lucide lucide-send w-4 h-4"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> </a>
                                    </div>
                                </div>
                                <!-- END: Chat Active -->
                                <!-- BEGIN: Chat Default -->
                                <div class="h-full flex items-center">
                                    <div class="mx-auto text-center">
                                        <div class="w-16 h-16 flex-none image-fit rounded-full overflow-hidden mx-auto">
                                            <img alt="Midone - HTML Admin Template" src="dist/images/profile-11.jpg">
                                        </div>
                                        <div class="mt-3">
                                            <div class="font-medium">Hey, Kevin Spacey!</div>
                                            <div class="text-slate-500 mt-1">Please select a chat to start messaging.</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Chat Default -->
                            </div>
                        </div>
                        <!-- END: Chat Content -->
                    </div>

<!-- BEGIN: Notification Toasts -->
<x-notification-toast 
    id="success" 
    type="success" 
    title="Success!" 
    message="Message sent successfully" 
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
    message="An error occurred while sending message" 
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
    message="Password changed successfully" 
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
    message="Profile updated successfully" 
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
    message="An error occurred while sending message" 
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
    message="An error occurred while sending message" 
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
    message="Photo uploaded successfully" 
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
    <script src="{{ asset('js/chat/chat.js') }}"></script>
@endpush