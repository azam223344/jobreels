// Function to update the notification count via AJAX
function updateNotificationCount() {
    $.ajax({
        url: '/notification-count', // Replace with the actual route for your Livewire component
        method: 'GET',
        success: function (data) {
            document.querySelector('.notification-count span').textContent = data.count;
        },
        error: function (xhr, status, error) {
            console.error('Error fetching notification count:', error);
        }
    });
}

// Periodically update the count every 10 seconds (adjust as needed)
setInterval(function () {
    updateNotificationCount();
}, 60000); // 10 seconds
