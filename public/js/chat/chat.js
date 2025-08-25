// Chat System JavaScript - Works with existing chat.blade.php template
document.addEventListener('DOMContentLoaded', function() {
    // Initialize chat system
    initializeChat();
    
    // Set up auto-refresh for conversations (longer interval to prevent conflicts)
    setInterval(loadConversations, 60000); // Refresh every 60 seconds instead of 30
});

// Global variables
let currentChatUser = null;
let currentConversation = null;
let conversations = [];
let isLoadingConversations = false; // Prevent multiple rapid calls

// Initialize chat system
function initializeChat() {
    // Load initial data
    loadConversations();
    loadUsers();
    loadUsersForChatsTab(); // Load users for the chats tab
    
    // Set up event listeners
    setupEventListeners();
    
    // Show default chat state
    showDefaultChat();
}

// Set up event listeners
function setupEventListeners() {
    // Search functionality for chats tab
    const chatSearchInput = document.querySelector('#chats .relative.text-slate-500 input');
    if (chatSearchInput) {
        chatSearchInput.addEventListener('input', function(e) {
            filterConversations(e.target.value);
            filterUsersInChatsTab(e.target.value); // Also filter users in the overflow section
        });
    }
    
    // Search functionality for friends tab
    const friendsSearchInput = document.querySelector('#friends .relative.text-slate-500 input');
    if (friendsSearchInput) {
        friendsSearchInput.addEventListener('input', function(e) {
            filterUsers(e.target.value);
        });
    }
    
    // Profile tab click event
    const profileTab = document.querySelector('#profile-tab');
    if (profileTab) {
        profileTab.addEventListener('click', function() {
            // Update profile tab with current chat user when tab is clicked
            if (currentChatUser) {
                updateProfileTab(currentChatUser);
            }
        });
    }
    
    // Message input
    const messageInput = document.querySelector('.chat__box__input');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Focus on input when typing to ensure good UX
        messageInput.addEventListener('focus', function() {
            // Scroll to bottom when user starts typing
            const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
            if (chatBox) {
                setTimeout(() => scrollToBottom(chatBox), 100);
            }
        });
    }
    
    // Send button
    const sendButton = document.querySelector('.chat__box .lucide-send').closest('a');
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }
    
    // Add scroll event listener to chat box for better UX
    const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
    if (chatBox) {
        chatBox.addEventListener('scroll', function() {
            // Track scroll position for smart scrolling
            const isAtBottom = isUserAtBottom(chatBox);
            if (isAtBottom) {
                chatBox.classList.add('at-bottom');
            } else {
                chatBox.classList.remove('at-bottom');
            }
        });
    }
}

// Load conversations
function loadConversations() {
    if (isLoadingConversations) {
        console.log('Already loading conversations, skipping...'); // Debug log
        return;
    }
    
    isLoadingConversations = true;
    console.log('Loading conversations...'); // Debug log
    
    fetch('/chat/get-conversations', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        return response.json();
    })
    .then(data => {
        console.log('Conversations data received:', data); // Debug log
        if (data.success) {
            conversations = data.conversations;
            console.log('Setting conversations:', conversations); // Debug log
            displayConversations(conversations);
        } else {
            console.error('Error loading conversations:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading conversations:', error);
    })
    .finally(() => {
        isLoadingConversations = false;
    });
}

// Load users for friends tab
function loadUsers() {
    fetch('/chat/get-users', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayUsers(data.users);
        } else {
            console.error('Error loading users:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Load users for the chats tab (for the "No conversations" message)
function loadUsersForChatsTab() {
    fetch('/chat/get-users', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayUsersInChatsTab(data.users);
        } else {
            console.error('Error loading users for chats tab:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading users for chats tab:', error);
    });
}

// Display users in the chats tab overflow-x-auto section
function displayUsersInChatsTab(users) {
    const usersContainer = document.querySelector('#chats .overflow-x-auto.scrollbar-hidden .flex');
    if (!usersContainer) {
        console.error('Users container not found in chats tab');
        return;
    }
    
    // Clear existing content
    usersContainer.innerHTML = '';
    
    if (!users || users.length === 0) {
        usersContainer.innerHTML = `
            <div class="text-center text-slate-500 py-4 w-full">
                <div class="text-sm">No users available</div>
            </div>
        `;
        return;
    }
    
    // Display each user as a clickable avatar
    users.forEach(user => {
        const userElement = createUserAvatarElement(user);
        usersContainer.appendChild(userElement);
    });
}

// Create user avatar element for the chats tab
function createUserAvatarElement(user) {
    const div = document.createElement('div');
    div.className = 'w-10 mr-4 cursor-pointer';
    div.onclick = () => startNewConversation(user);
    
    div.innerHTML = `
        <div class="w-10 h-10 flex-none image-fit rounded-full">
            <img alt="${user.name}" class="rounded-full" src="${user.photo_url}">
            <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
        </div>
        <div class="text-xs text-slate-500 truncate text-center mt-2">${user.name}</div>
    `;
    
    return div;
}

// Display conversations in the chat list
function displayConversations(conversations) {
    const chatList = document.querySelector('.chat__chat-list');
    if (!chatList) {
        console.error('Chat list element not found');
        return;
    }
    
    console.log('Displaying conversations:', conversations); // Debug log
    
    // Clear existing conversations and any "no conversations" messages
    const existingConversations = chatList.querySelectorAll('.intro-x.cursor-pointer.box.relative.flex.items-center.p-5.mt-5');
    const noConversationsMessage = chatList.querySelector('.text-center.text-slate-500.py-8');
    
    existingConversations.forEach(conv => conv.remove());
    if (noConversationsMessage) {
        noConversationsMessage.remove();
    }
    
    // Check if conversations exist and have length
    if (!conversations || !Array.isArray(conversations) || conversations.length === 0) {
        console.log('No conversations to display, showing empty state'); // Debug log
        chatList.innerHTML += `
            <div class="text-center text-slate-500 py-8">
                <div class="text-lg font-medium">No conversations yet</div>
                <div class="text-sm mt-2">Start a chat with someone from the Friends tab</div>
            </div>
        `;
        return;
    }
    
    console.log(`Displaying ${conversations.length} conversations`); // Debug log
    
    conversations.forEach((conversation, index) => {
        console.log(`Creating conversation element ${index + 1}:`, conversation); // Debug log
        const conversationElement = createConversationElement(conversation);
        chatList.appendChild(conversationElement);
    });
}

// Create conversation element using your template structure
function createConversationElement(conversation) {
    const div = document.createElement('div');
    div.className = 'intro-x cursor-pointer box relative flex items-center p-5 mt-5';
    div.onclick = () => openConversation(conversation);
    
    const unreadBadge = conversation.unread_count > 0 ? 
        `<div class="w-5 h-5 flex items-center justify-center absolute top-0 right-0 text-xs text-white rounded-full bg-primary font-medium -mt-1 -mr-1">${conversation.unread_count}</div>` : '';
    
    div.innerHTML = `
        <div class="w-12 h-12 flex-none image-fit mr-1">
            <img alt="Profile" class="rounded-full" src="${conversation.user_photo}">
            <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
        </div>
        <div class="ml-2 overflow-hidden flex-1">
            <div class="flex items-center justify-between">
                <a href="javascript:;" class="font-medium">${conversation.user_name}</a> 
                <div class="text-xs text-slate-400 ml-4">${conversation.last_message_time}</div>
            </div>
            <div class="w-full truncate text-slate-500 mt-0.5">${conversation.last_message}</div>
        </div>
        ${unreadBadge}
    `;
    
    return div;
}

// Display users in the friends tab using your template structure
function displayUsers(users) {
    const userList = document.querySelector('.chat__user-list');
    if (!userList) return;
    
    // Clear existing users but keep the search box
    const existingUsers = userList.querySelectorAll('.cursor-pointer.box.relative.flex.items-center.p-5.mt-5');
    existingUsers.forEach(user => user.remove());
    
    if (users.length === 0) {
        userList.innerHTML += `
            <div class="text-center text-slate-500 py-8">
                <div class="text-lg font-medium">No users found</div>
            </div>
        `;
        return;
    }
    
    // Group users by first letter
    const groupedUsers = groupUsersByLetter(users);
    
    Object.keys(groupedUsers).forEach(letter => {
        userList.innerHTML += `<div class="mt-4 text-slate-500">${letter}</div>`;
        
        groupedUsers[letter].forEach(user => {
            const userElement = createUserElement(user);
            userList.appendChild(userElement);
        });
    });
}

// Group users by first letter of name
function groupUsersByLetter(users) {
    return users.reduce((groups, user) => {
        const firstLetter = user.name.charAt(0).toUpperCase();
        if (!groups[firstLetter]) {
            groups[firstLetter] = [];
        }
        groups[firstLetter].push(user);
        return groups;
    }, {});
}

// Create user element for friends tab using your template structure
function createUserElement(user) {
    const div = document.createElement('div');
    div.className = 'cursor-pointer box relative flex items-center p-5 mt-5';
    div.onclick = () => startNewConversation(user);
    
    div.innerHTML = `
        <div class="w-12 h-12 flex-none image-fit mr-1">
            <img alt="Profile" class="rounded-full" src="${user.photo_url}">
            <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
        </div>
        <div class="ml-2 overflow-hidden flex-1">
            <div class="flex items-center"> 
                <a href="javascript:;" class="font-medium">${user.name}</a> 
            </div>
            <div class="w-full truncate text-slate-500 mt-0.5">
                ${user.department ? user.department.name : 'General'} • ${user.position ? user.position.name : 'User'}
            </div>
        </div>
    `;
    
    return div;
}

// Update profile tab with current chat user information
function updateProfileTab(user) {
    const profileContent = document.getElementById('profile-content');
    const dynamicProfileContent = document.getElementById('dynamic-profile-content');
    
    if (!user) {
        // Show default state when no user is selected
        if (profileContent) profileContent.classList.remove('hidden');
        if (dynamicProfileContent) dynamicProfileContent.classList.add('hidden');
        return;
    }
    
    // Hide default state and show dynamic content
    if (profileContent) profileContent.classList.add('hidden');
    if (dynamicProfileContent) dynamicProfileContent.classList.remove('hidden');
    
    // Update profile information
    const profilePhoto = document.getElementById('profile-photo');
    const profileName = document.getElementById('profile-name');
    const profileStatus = document.getElementById('profile-status');
    const profileUserId = document.getElementById('profile-user-id');
    const profileEmail = document.getElementById('profile-email');
    const profileRole = document.getElementById('profile-role');
    const profileDepartment = document.getElementById('profile-department');
    
    if (profilePhoto) profilePhoto.src = user.photo || user.photo_url || 'dist/images/profile-11.jpg';
    if (profileName) profileName.textContent = user.name || 'Unknown User';
    if (profileStatus) profileStatus.textContent = 'Online';
    if (profileUserId) profileUserId.textContent = user.id || '-';
    if (profileEmail) profileEmail.textContent = user.email || '-';
    
    // Handle role and department from relationships
    let roleText = 'User';
    let departmentText = 'General';
    
    if (user.user_role) {
        roleText = user.user_role;
    } else if (user.position && user.position.name) {
        roleText = user.position.name;
    }
    
    if (user.user_department) {
        departmentText = user.user_department;
    } else if (user.department && user.department.name) {
        departmentText = user.department.name;
    }
    
    if (profileRole) profileRole.textContent = roleText;
    if (profileDepartment) profileDepartment.textContent = departmentText;
}

// Enhanced openConversation function to also update profile tab
function openConversation(conversation) {
    console.log('Opening conversation with:', conversation); // Debug log
    
    // Prevent multiple rapid calls
    if (currentChatUser && currentChatUser.id === conversation.user_id) {
        console.log('Already in conversation with this user, skipping...'); // Debug log
        return;
    }
    
    currentChatUser = {
        id: conversation.user_id,
        name: conversation.user_name,
        photo: conversation.user_photo,
        email: conversation.user_email,
        role: conversation.user_role,
        department: conversation.user_department,
        created_at: conversation.user_created_at
    };
    
    console.log('Current chat user set to:', currentChatUser); // Debug log
    
    // Clear chat area before loading new messages
    clearChatArea();
    loadMessages(conversation.user_id);
    showChatInterface();
    updateChatHeader();
    
    // Update profile tab with user information
    updateProfileTab(currentChatUser);
}

// Enhanced startNewConversation function to also update profile tab
function startNewConversation(user) {
    console.log('Starting new conversation with:', user); // Debug log
    
    // Prevent multiple rapid calls
    if (currentChatUser && currentChatUser.id === user.id) {
        console.log('Already in conversation with this user, skipping...'); // Debug log
        return;
    }
    
    // Create a user object with the correct structure for the profile tab
    currentChatUser = {
        id: user.id,
        name: user.name,
        photo: user.photo_url,
        email: user.email,
        role: user.position ? user.position.name : 'User',
        department: user.department ? user.department.name : 'General',
        created_at: user.created_at
    };
    
    console.log('Current chat user set to:', currentChatUser); // Debug log
    
    showChatInterface();
    updateChatHeader();
    clearChatArea(); // Use clearChatArea instead of clearMessages
    
    // Update profile tab with user information
    updateProfileTab(currentChatUser);
    
    // Mark as read if there are any existing messages
    if (user.id) {
        markMessagesAsRead(user.id);
    }
}

// Load messages for a conversation
function loadMessages(otherUserId) {
    fetch('/chat/get-messages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            other_user_id: otherUserId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayMessages(data.messages);
        } else {
            console.error('Error loading messages:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Display messages in chat using your template structure
function displayMessages(messages) {
    const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
    if (!chatBox) return;
    
    console.log('Displaying messages:', messages); // Debug log
    
    // Clear ALL existing content including messages and date separators
    chatBox.innerHTML = '';
    
    if (!messages || messages.length === 0) {
        chatBox.innerHTML = `
            <div class="text-center text-slate-500 py-8">
                <div class="text-lg font-medium">No messages yet</div>
                <div class="text-sm mt-2">Start the conversation!</div>
            </div>
        `;
        // Scroll to bottom even for empty state
        setTimeout(() => scrollToBottom(chatBox), 100);
        return;
    }
    
    let currentDate = '';
    
    messages.forEach((message, index) => {
        // Add date separator if date changes
        if (message.date !== currentDate) {
            currentDate = message.date;
            const dateSeparator = document.createElement('div');
            dateSeparator.className = 'text-slate-400 dark:text-slate-500 text-xs text-center mb-10 mt-5';
            dateSeparator.textContent = message.date;
            chatBox.appendChild(dateSeparator);
        }
        
        const messageElement = createMessageElement(message);
        chatBox.appendChild(messageElement);
        
        // Add clear-both div after each message to maintain proper layout
        const clearDiv = document.createElement('div');
        clearDiv.className = 'clear-both';
        chatBox.appendChild(clearDiv);
    });
    
    // Scroll to bottom with a small delay to ensure DOM is updated
    setTimeout(() => scrollToBottom(chatBox), 100);
}

// Improved scroll to bottom function
function scrollToBottom(chatBox) {
    if (!chatBox) return;
    
    // Use smooth scrolling for better user experience
    chatBox.scrollTo({
        top: chatBox.scrollHeight,
        behavior: 'smooth'
    });
    
    // Fallback for browsers that don't support smooth scrolling
    if (!('scrollBehavior' in document.documentElement.style)) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
}

// Check if user is at bottom of chat
function isUserAtBottom(chatBox) {
    if (!chatBox) return true;
    
    const threshold = 50; // 50px threshold
    return (chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight) < threshold;
}

// Smart scroll to bottom - only auto-scroll if user is already at bottom
function smartScrollToBottom(chatBox, force = false) {
    if (!chatBox) return;
    
    // If user has scrolled up and we're not forcing scroll, don't auto-scroll
    if (!force && !isUserAtBottom(chatBox)) {
        console.log('User has scrolled up, not auto-scrolling to bottom');
        return;
    }
    
    scrollToBottom(chatBox);
}

// Create message element using your template structure
function createMessageElement(message) {
    const div = document.createElement('div');
    div.className = `chat__box__text-box flex items-end ${message.is_own ? 'float-right' : 'float-left'} mb-4`;
    
    if (message.is_own) {
        div.innerHTML = `
           
            <div class="bg-primary px-4 py-3 text-white rounded-l-md rounded-t-md">
                ${message.message}
                <div class="mt-1 text-xs text-white text-opacity-80">${message.time}</div>
            </div>
            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative ml-5">
                <img alt="Profile" class="rounded-full" src="${message.sender_photo}">
            </div>
        `;
    } else {
        div.innerHTML = `
            <div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
                <img alt="Profile" class="rounded-full" src="${message.sender_photo}">
            </div>
            <div class="bg-slate-100 dark:bg-darkmode-400 px-4 py-3 text-slate-500 rounded-r-md rounded-t-md">
                ${message.message}
                <div class="mt-1 text-xs text-slate-500">${message.time}</div>
            </div>
        `;
    }
    
    return div;
}

// Send message
function sendMessage() {
    if (!currentChatUser) {
        showNotification_error();
        return;
    }
    
    const messageInput = document.querySelector('.chat__box__input');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Show typing indicator
    showTypingIndicator();
    
    fetch('/chat/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            to_user_id: currentChatUser.id,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add message to chat
            addMessageToChat(data.chat_message);
            
            // Clear input
            messageInput.value = '';
            
            // Update conversations list
            loadConversations();
            
            // Hide typing indicator
            hideTypingIndicator();
            
            // Show success notification
            showNotification_success();
        } else {
            showNotification_error();
            hideTypingIndicator();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification_error();
        hideTypingIndicator();
    });
}

// Add message to chat
function addMessageToChat(chatMessage) {
    const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
    if (!chatBox) return;
    
    const messageElement = createMessageElement(chatMessage);
    chatBox.appendChild(messageElement);
    
    // Use smart scrolling - only auto-scroll if user is at bottom
    smartScrollToBottom(chatBox);
    
    // Additional scroll after DOM update to ensure we're at the very bottom if needed
    setTimeout(() => smartScrollToBottom(chatBox), 50);
}

// Show typing indicator using your template structure
function showTypingIndicator() {
    const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
    if (!chatBox) return;
    
    const typingDiv = document.createElement('div');
    typingDiv.className = 'chat__box__text-box flex items-end float-left mb-4';
    typingDiv.id = 'typing-indicator';
    
    typingDiv.innerHTML = `
        <div class="w-10 h-10 hidden sm:block flex-none image-fit relative mr-5">
            <img alt="Profile" class="rounded-full" src="${currentChatUser.photo}">
        </div>
        <div class="bg-slate-100 dark:bg-darkmode-400 px-4 py-3 text-slate-500 rounded-r-md rounded-t-md">
            ${currentChatUser.name} is typing 
            <span class="typing-dots ml-1"> <span>.</span> <span>.</span> <span>.</span> </span>
        </div>
    `;
    
    chatBox.appendChild(typingDiv);
    
    // Scroll to bottom to show typing indicator
    scrollToBottom(chatBox);
}

// Hide typing indicator
function hideTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Mark messages as read
function markMessagesAsRead(fromUserId) {
    fetch('/chat/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            from_user_id: fromUserId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh conversations to update unread counts
            loadConversations();
        }
    })
    .catch(error => {
        console.error('Error marking messages as read:', error);
    });
}

// Show chat interface
function showChatInterface() {
    const chatBox = document.querySelector('.chat__box');
    if (chatBox) {
        // Remove hidden class from chat active
        const chatActive = chatBox.querySelector('.hidden.h-full.flex.flex-col');
        if (chatActive) {
            chatActive.classList.remove('hidden');
        }
        
        // Hide default chat
        const defaultChat = chatBox.querySelector('.h-full.flex.items-center');
        if (defaultChat) {
            defaultChat.style.display = 'none';
        }
        
        // Ensure chat scrolls to bottom when interface is shown
        setTimeout(() => {
            const scrollBox = chatBox.querySelector('.overflow-y-scroll');
            if (scrollBox) {
                scrollToBottom(scrollBox);
            }
        }, 200);
    }
}

// Show default chat state
function showDefaultChat() {
    const chatBox = document.querySelector('.chat__box');
    if (chatBox) {
        // Hide chat active
        const chatActive = chatBox.querySelector('.h-full.flex.flex-col');
        if (chatActive) {
            chatActive.classList.add('hidden');
        }
        
        // Show default chat
        const defaultChat = chatBox.querySelector('.h-full.flex.items-center');
        if (defaultChat) {
            defaultChat.style.display = 'flex';
        }
    }
    
    // Reset profile tab to default state
    updateProfileTab(null);
}

// Update chat header using your template structure
function updateChatHeader() {
    if (!currentChatUser) return;
    
    const chatHeader = document.querySelector('.chat__box .flex.flex-col.sm\\:flex-row');
    if (chatHeader) {
        const userInfo = chatHeader.querySelector('.flex.items-center');
        if (userInfo) {
            const photo = userInfo.querySelector('.w-10.h-10 img, .w-12.h-12 img');
            const name = userInfo.querySelector('.font-medium');
            const status = userInfo.querySelector('.text-slate-500');
            
            if (photo) photo.src = currentChatUser.photo;
            if (name) name.textContent = currentChatUser.name;
            if (status) status.innerHTML = 'Online <span class="mx-1">•</span> <span class="text-success">Active</span>';
        }
    }
}

// Clear messages completely
function clearMessages() {
    const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
    if (chatBox) {
        // Clear everything completely
        chatBox.innerHTML = `
            <div class="text-center text-slate-500 py-8">
                <div class="text-lg font-medium">Start a new conversation</div>
                <div class="text-sm mt-2">Type your first message below</div>
            </div>
        `;
    }
}

// Clear chat area completely (for switching conversations)
function clearChatArea() {
    const chatBox = document.querySelector('.chat__box .overflow-y-scroll');
    if (chatBox) {
        chatBox.innerHTML = '';
    }
}

// Filter conversations
function filterConversations(searchTerm) {
    if (!searchTerm) {
        displayConversations(conversations);
        return;
    }
    
    const filtered = conversations.filter(conversation => 
        conversation.user_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        conversation.last_message.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    displayConversations(filtered);
}

// Filter users
function filterUsers(searchTerm) {
    // This would need to be implemented if you want to filter users in real-time
    // For now, just reload all users
    loadUsers();
}

// Filter users in the chats tab overflow-x-auto section
function filterUsersInChatsTab(searchTerm) {
    if (!searchTerm) {
        // If no search term, reload all users
        loadUsersForChatsTab();
        return;
    }
    
    // For now, just reload all users when searching
    // This can be improved later to filter from existing data
    loadUsersForChatsTab();
}

// Refresh users in chats tab (for manual testing)
function refreshUsersInChatsTab() {
    console.log('Refreshing users in chats tab...');
    loadUsersForChatsTab();
}

// Delete message (placeholder function)
function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message?')) {
        // Implement message deletion
        console.log('Delete message:', messageId);
    }
}

// Reply to message (placeholder function)
function replyToMessage(senderName, message) {
    const messageInput = document.querySelector('.chat__box__input');
    if (messageInput) {
        messageInput.value = `Replying to ${senderName}: ${message}`;
        messageInput.focus();
    }
}

// Notification functions using your existing notification system
function showNotification_success() {
    if (typeof showNotification_success !== 'undefined') {
        showNotification_success();
    } else {
        console.log('Success notification');
    }
}

function showNotification_error() {
    if (typeof showNotification_error !== 'undefined') {
        showNotification_error();
    } else {
        console.log('Error notification');
    }
}

// Test function to manually check conversations (for debugging)
function testConversations() {
    console.log('=== TESTING CONVERSATIONS ===');
    console.log('Current conversations array:', conversations);
    console.log('Chat list element:', document.querySelector('.chat__chat-list'));
    console.log('Calling loadConversations manually...');
    loadConversations();
}

// Make functions globally available
window.openConversation = openConversation;
window.startNewConversation = startNewConversation;
window.sendMessage = sendMessage;
window.deleteMessage = deleteMessage;
window.replyToMessage = replyToMessage;
window.testConversations = testConversations; // Add test function
window.refreshUsersInChatsTab = refreshUsersInChatsTab; // Add refresh function
window.updateProfileTab = updateProfileTab; // Add profile update function
