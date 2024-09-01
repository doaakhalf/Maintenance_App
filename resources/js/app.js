import './bootstrap';


    
window.Echo.channel('maintenance-request.' + userId)
    .listen('.MaintenanceRequestCreated', (data) => {
        console.log(data,typeof(data));

      
        const notifications = document.getElementById('notifications');
        const notification = document.createElement('a');
        notification.classList.add('dropdown-item')
        notification.href=data.url
        console.log(notification.getAttribute("href"));
      notification.innerHTML=` <i class="fas fa-envelope mr-2"></i> `+data.name +`
      <span class="float-right text-muted text-sm">3 mins</span>`
        // notifications.appendChild(notification);
        $('#notifications > a:first').before(notification)
      
       const notifications_counter = document.getElementById('notifications-counter');
       const notifications_counter_badge = document.getElementById('notifications-counter-badge');

        notifications_counter.innerText= parseInt(notifications_counter.innerText)+1;
        notifications_counter_badge.innerText=parseInt(notifications_counter_badge.innerText)+1;
        notifications_counter_badge.style='display:block'
    });
    window.Echo.channel('maintenance-perform.' + userId)
    .listen('.MaintenancePerformCreated', (data) => {
        console.log(data,typeof(data));
       
        const notifications = document.getElementById('notifications');
        const notification = document.createElement('a');
        notification.href=data.url
        notification.classList.add('dropdown-item')

      notification.innerHTML=` <i class="fas fa-envelope mr-2"></i> `+data.name +`
      <span class="float-right text-muted text-sm">3 mins</span>`
        // notifications.appendChild(notification);
        $('#notifications > a:first').before(notification)
      
       const notifications_counter = document.getElementById('notifications-counter');
       const notifications_counter_badge = document.getElementById('notifications-counter-badge');

        notifications_counter.innerText= parseInt(notifications_counter.innerText)+1;
        notifications_counter_badge.innerText=parseInt(notifications_counter_badge.innerText)+1;
        notifications_counter_badge.style='display:block'

    });

    window.Echo.channel('maintenance-request-change-status.' + userId)
    .listen('.MaintenanceRequestStatusChanged', (data) => {
        console.log(data,typeof(data));
       
        const notifications = document.getElementById('notifications');
        const notification = document.createElement('a');
        notification.href=data.url
        notification.classList.add('dropdown-item')
        console.log(notification.getAttribute("href"));

      notification.innerHTML=` <i class="fas fa-envelope mr-2"></i> `+data.name +`
      <span class="float-right text-muted text-sm">3 mins</span>`
        // notifications.appendChild(notification);
        $('#notifications > a:first').before(notification)
      
       const notifications_counter = document.getElementById('notifications-counter');
       const notifications_counter_badge = document.getElementById('notifications-counter-badge');

        notifications_counter.innerText= parseInt(notifications_counter.innerText)+1;
        notifications_counter_badge.innerText=parseInt(notifications_counter_badge.innerText)+1;
        notifications_counter_badge.style='display:block'

    });
    window.Echo.channel('maintenance-perform-change-status.' + userId)
    .listen('.MaintenancePerformStatusChanged', (data) => {
        console.log(data,typeof(data));
       
        const notifications = document.getElementById('notifications');
        const notification = document.createElement('a');
        notification.href=data.url
        notification.classList.add('dropdown-item')
        console.log(notification.getAttribute("href"));

      notification.innerHTML=` <i class="fas fa-envelope mr-2"></i> `+data.name +`
      <span class="float-right text-muted text-sm">3 mins</span>`
        // notifications.appendChild(notification);
        $('#notifications > a:first').before(notification)
      
       const notifications_counter = document.getElementById('notifications-counter');
       const notifications_counter_badge = document.getElementById('notifications-counter-badge');

        notifications_counter.innerText= parseInt(notifications_counter.innerText)+1;
        notifications_counter_badge.innerText=parseInt(notifications_counter_badge.innerText)+1;
        notifications_counter_badge.style='display:block'

    });