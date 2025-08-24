document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard functionality
    initializeDashboard();
});

function initializeDashboard() {
    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }
    
    // Initialize datepicker if available
    if (typeof Litepicker !== 'undefined') {
        initializeDatepicker();
    }
    
    // Initialize other dashboard components
    initializeComponents();
}

function initializeCharts() {
    // Line Chart
    const lineChart = document.getElementById('report-line-chart');
    if (lineChart) {
        new Chart(lineChart, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [65, 59, 80, 81, 56, 55],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Pie Chart
    const pieChart = document.getElementById('report-pie-chart');
    if (pieChart) {
        new Chart(pieChart, {
            type: 'pie',
            data: {
                labels: ['17-30 Years', '31-50 Years', '50+ Years'],
                datasets: [{
                    data: [62, 33, 10],
                    backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Donut Chart
    const donutChart = document.getElementById('report-donut-chart');
    if (donutChart) {
        new Chart(donutChart, {
            type: 'doughnut',
            data: {
                labels: ['17-30 Years', '31-50 Years', '50+ Years'],
                datasets: [{
                    data: [62, 33, 10],
                    backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Small Donut Charts
    const donutChart1 = document.getElementById('report-donut-chart-1');
    if (donutChart1) {
        new Chart(donutChart1, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Remaining'],
                datasets: [{
                    data: [20, 80],
                    backgroundColor: ['#10b981', '#e5e7eb']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }
    
    const donutChart2 = document.getElementById('report-donut-chart-2');
    if (donutChart2) {
        new Chart(donutChart2, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Remaining'],
                datasets: [{
                    data: [45, 55],
                    backgroundColor: ['#f59e0b', '#e5e7eb']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }
    
    // Simple Line Charts
    const simpleLineCharts = document.querySelectorAll('.simple-line-chart-1');
    simpleLineCharts.forEach(chart => {
        new Chart(chart, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', '', '', '', '', '', '', ''],
                datasets: [{
                    data: [0, 2, 4, 3, 5, 4, 6, 5, 7, 6, 8, 7],
                    borderColor: '#3b82f6',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        });
    });
}

function initializeDatepicker() {
    const datepicker = document.querySelector('.datepicker');
    if (datepicker) {
        new Litepicker({
            element: datepicker,
            singleMode: true,
            numberOfMonths: 1,
            numberOfColumns: 1,
            format: 'DD/MM/YYYY'
        });
    }
}

function initializeComponents() {
    // Initialize sidebar menu functionality
    initializeSidebarMenu();
    
    // Initialize tooltips
    if (typeof tippy !== 'undefined') {
        tippy('[data-tippy-content]', {
            theme: 'light'
        });
    }
    
    // Initialize dropdowns
    const dropdowns = document.querySelectorAll('[data-tw-toggle="dropdown"]');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            if (menu && menu.classList.contains('dropdown-menu')) {
                menu.classList.toggle('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
            openDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
    
    // Initialize reload button
    const reloadBtn = document.querySelector('[href=""]');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            location.reload();
        });
    }
}

function initializeSidebarMenu() {
    // Get all menu items with sub-menus
    const menuItems = document.querySelectorAll('.side-menu[href="javascript:;"]');
    
    menuItems.forEach(menuItem => {
        menuItem.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Find the sub-menu (next sibling ul)
            const subMenu = this.nextElementSibling;
            if (subMenu && subMenu.tagName === 'UL') {
                // Toggle the sub-menu
                subMenu.classList.toggle('side-menu__sub-open');
                
                // Toggle the chevron icon rotation
                const chevron = this.querySelector('.side-menu__sub-icon');
                if (chevron) {
                    chevron.classList.toggle('transform');
                    chevron.classList.toggle('rotate-180');
                }
                
                // Close other open menus (optional - comment out if you want multiple menus open)
                closeOtherMenus(subMenu);
            }
        });
    });
    
    // Handle nested sub-menus (sub-sub-menus)
    const nestedMenuItems = document.querySelectorAll('.side-menu__sub-open .side-menu[href="javascript:;"]');
    nestedMenuItems.forEach(nestedItem => {
        nestedItem.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Find the nested sub-menu
            const nestedSubMenu = this.nextElementSibling;
            if (nestedSubMenu && nestedSubMenu.tagName === 'UL') {
                // Toggle the nested sub-menu
                nestedSubMenu.classList.toggle('side-menu__sub-open');
                
                // Toggle the chevron icon rotation
                const chevron = this.querySelector('.side-menu__sub-icon');
                if (chevron) {
                    chevron.classList.toggle('transform');
                    chevron.classList.toggle('rotate-180');
                }
            }
        });
    });
}

function closeOtherMenus(currentMenu) {
    // Get all open sub-menus
    const openMenus = document.querySelectorAll('.side-menu__sub-open');
    
    openMenus.forEach(menu => {
        if (menu !== currentMenu) {
            menu.classList.remove('side-menu__sub-open');
            
            // Reset chevron rotation for closed menus
            const parentMenuItem = menu.previousElementSibling;
            if (parentMenuItem && parentMenuItem.classList.contains('side-menu')) {
                const chevron = parentMenuItem.querySelector('.side-menu__sub-icon');
                if (chevron) {
                    chevron.classList.remove('transform', 'rotate-180');
                }
            }
        }
    });
}

// Export functions for global access if needed
window.Dashboard = {
    initialize: initializeDashboard,
    reload: () => location.reload()
};
