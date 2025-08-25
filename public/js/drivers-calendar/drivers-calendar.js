// Drivers Calendar JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DRIVERS CALENDAR JAVASCRIPT LOADING ===');
    initializeCalendar();
});

// Initialize FullCalendar
function initializeCalendar() {
    const calendarEl = document.getElementById('drivers-calendar');
    if (!calendarEl) {
        console.error('Calendar element not found');
        return;
    }

    // Get events from PHP
    const events = window.calendarEvents || [];
    console.log('Calendar events:', events);

    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: events,
        eventClick: function(info) {
            showEventDetails(info.event.id);
        },
        eventDidMount: function(info) {
            // Add basic tooltip functionality without external library
            const event = info.event;
            const eventEl = info.el;
            
            // Create tooltip content
            const tooltipContent = `${event.title}\n${event.extendedProps.destination || 'No destination'}\n${event.extendedProps.reason || 'No reason'}`;
            
            // Add title attribute for basic tooltip
            eventEl.setAttribute('title', tooltipContent);
            
            // Add status-based CSS class for styling
            if (event.extendedProps.status) {
                eventEl.classList.add(`status-${event.extendedProps.status}`);
            }
            
            // Add border styling to make events more visible
            eventEl.style.border = '2px solid #3b82f6'; // Blue border
            eventEl.style.borderRadius = '4px';
            eventEl.style.boxShadow = '0 2px 4px rgba(59, 130, 246, 0.2)';
        },
        height: 'auto',
        editable: false,
        selectable: false,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        firstDay: 1, // Monday
        locale: 'en',
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List'
        }
    });

    // Render the calendar
    calendar.render();
    
    // Store calendar instance globally
    window.driversCalendar = calendar;
    
    // Update sidebar events count
    updateSidebarEventsCount();
}

// Show event details modal
function showEventDetails(eventId) {
    // Find the event data
    const events = window.calendarEvents || [];
    const event = events.find(e => e.id == eventId);
    
    if (!event) {
        console.error('Event not found:', eventId);
        return;
    }

    // Populate modal with event details
    document.getElementById('event-driver-name').textContent = event.driver_name || 'Unknown Driver';
    document.getElementById('event-vehicle-name').textContent = event.vehicle_name || 'Unknown Vehicle';
    document.getElementById('event-requester-name').textContent = event.requester_name || 'Unknown User';
    document.getElementById('event-destination').textContent = event.destination || 'No destination specified';
    document.getElementById('event-reason').textContent = event.reason || 'No reason specified';
    
    // Format dates
    const startDate = new Date(event.start);
    const endDate = new Date(event.end);
    
    document.getElementById('event-start-datetime').textContent = startDate.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    document.getElementById('event-end-datetime').textContent = endDate.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Show status with color
    const statusElement = document.getElementById('event-status');
    statusElement.textContent = event.status.charAt(0).toUpperCase() + event.status.slice(1);
    statusElement.style.color = event.backgroundColor;
    
    // Show remarks if available
    const remarksContainer = document.getElementById('event-remarks-container');
    const remarksElement = document.getElementById('event-remarks');
    
    if (event.extendedProps.remarks) {
        remarksElement.textContent = event.extendedProps.remarks;
        remarksContainer.style.display = 'block';
    } else {
        remarksContainer.style.display = 'none';
    }
    
    // Store current event ID for edit function
    window.currentEventId = eventId;
    
    // Show the modal
    const modal = document.getElementById('event-details-modal');
    const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
    modalInstance.show();
}

// Edit schedule function
function editSchedule(eventId) {
    // Redirect to edit page or show edit modal
    console.log('Edit schedule:', eventId);
    // You can implement this based on your requirements
    // For now, just show the details modal
    showEventDetails(eventId);
}

// Edit schedule from modal
function editScheduleFromModal() {
    const eventId = window.currentEventId;
    if (eventId) {
        editSchedule(eventId);
    }
}

// Refresh calendar events
function refreshCalendarEvents() {
    if (window.driversCalendar) {
        // Reload the page to get fresh data
        location.reload();
    }
}

// Filter events by driver
function filterEventsByDriver(driverId) {
    if (window.driversCalendar) {
        const events = window.calendarEvents || [];
        const filteredEvents = driverId ? events.filter(e => e.extendedProps.driver_id == driverId) : events;
        
        // Remove existing events and add filtered ones
        window.driversCalendar.removeAllEvents();
        window.driversCalendar.addEventSource(filteredEvents);
    }
}

// Filter events by status
function filterEventsByStatus(status) {
    if (window.driversCalendar) {
        const events = window.calendarEvents || [];
        const filteredEvents = status ? events.filter(e => e.status === status) : events;
        
        // Remove existing events and add filtered ones
        window.driversCalendar.removeAllEvents();
        window.driversCalendar.addEventSource(filteredEvents);
    }
}

// Update sidebar events count
function updateSidebarEventsCount() {
    const events = window.calendarEvents || [];
    const sidebarEvents = document.getElementById('drivers-calendar-events');
    const noEventsElement = document.getElementById('drivers-calendar-no-events');
    
    if (sidebarEvents) {
        const eventElements = sidebarEvents.querySelectorAll('.event');
        if (eventElements.length === 0 && noEventsElement) {
            noEventsElement.style.display = 'block';
        } else if (noEventsElement) {
            noEventsElement.style.display = 'none';
        }
    }
}

// Export calendar to PDF/Excel
function exportCalendar(format = 'pdf') {
    if (window.driversCalendar) {
        console.log('Exporting calendar to:', format);
        // Implement export functionality based on your requirements
        // You can use libraries like jsPDF or SheetJS
    }
}

// Print calendar
function printCalendar() {
    if (window.driversCalendar) {
        console.log('Printing calendar');
        window.print();
    }
}
