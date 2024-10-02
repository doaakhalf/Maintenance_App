import './bootstrap';



import './bootstrap';

// Reusable function to handle notifications
function handleNotification(channel, eventClass, userId) {
    window.Echo.channel(channel + '.' + userId)
        .listen(eventClass, (data) => {
            console.log('Notification received:', data);

            const notifications = document.getElementById('notifications');
            const notification = document.createElement('a');
            notification.classList.add('dropdown-item');
          
            notification.href = data.url;
            
            notification.innerHTML = `<i class="fas fa-envelope mr-2"></i> ${data.name}
                <span class="float-right text-muted text-sm">3 mins</span>`;

            $('#notifications > a:first').before(notification);

            // Update the notification counter
            updateNotificationCounter();
        });
}

// Function to update notification counters
function updateNotificationCounter() {
    const notificationsCounter = document.getElementById('notifications-counter');
    const notificationsCounterBadge = document.getElementById('notifications-counter-badge');

    let counterValue = parseInt(notificationsCounter.innerText) || 0;

    counterValue++;
    notificationsCounter.innerText = counterValue;
    notificationsCounterBadge.innerText = counterValue;
    notificationsCounterBadge.style.display = 'block';
}

// Listen for notifications related to Maintenance Requests
handleNotification('maintenance-request', '.MaintenanceRequestCreated', userId);
handleNotification('maintenance-perform', '.MaintenancePerformCreated', userId);
handleNotification('maintenance-request-change-status', '.MaintenanceRequestStatusChanged', userId);
handleNotification('maintenance-perform-change-status', '.MaintenancePerformStatusChanged', userId);

// Listen for notifications related to Calibration Requests
handleNotification('calibration-request', '.CalibrationRequestCreated', userId);

handleNotification('assign-batch-maintenance-requests', '.AssignBatchRequest', userId);

handleNotification('notify-ppm-equipment', '.EquipmentPPMDueEvent', userId);




// 