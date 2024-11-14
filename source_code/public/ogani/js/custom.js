/* Navbar Start */
function toggleNotifications() {
    const dropdown = document.getElementById("notificationDropdown");
    dropdown.style.display = dropdown.style.display === "none" || dropdown.style.display === "" ? "block" : "none";
}

// Tutup dropdown jika klik di luar elemen dropdown
window.onclick = function(event) {
    if (!event.target.closest('.notification-item')) {
        const dropdown = document.getElementById("notificationDropdown");
        if (dropdown && dropdown.style.display === "block") {
            dropdown.style.display = "none";
        }
    }
}

function closeNotification(orderId) {
    const notification = document.getElementById(`notification-${orderId}`);
    if (notification) {
        notification.style.display = 'none';
    }
}

function closePaymentNotification(event, paymentId) {
    event.preventDefault(); // Prevent the link click
    const notification = document.getElementById(`payment-notification-${paymentId}`);
    if (notification) {
        notification.style.display = 'none';
    }
}


function sortProducts() {
    var sortBy = document.getElementById('sort-by').value;
    var url = new URL(window.location.href);
    url.searchParams.set('sort', sortBy);
    window.location.href = url.toString();
}

document.getElementById('grid-view').addEventListener('click', function() {
    document.getElementById('product-list').classList.remove('list-view');
    document.getElementById('product-list').classList.add('grid-view');
    document.getElementById('notification').style.display = 'none'; // Ensure notification is hidden in grid view
    console.log('Grid view activated, notification hidden.');
});

document.getElementById('list-view').addEventListener('click', function() {
    document.getElementById('product-list').classList.remove('grid-view');
    document.getElementById('product-list').classList.add('list-view');
    document.getElementById('notification').style.display = 'block'; // Show notification for list view
    console.log('List view activated, notification shown.');
});

document.addEventListener('DOMContentLoaded', function() {
    // Get all product items
    const productItems = document.querySelectorAll('.product__item');

    // Add a click event listener to each product card
    productItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Check if the clicked element is not one of the interactive elements
            if (!e.target.closest('a') && !e.target.closest('li')) {
                // If not, redirect to the product's detail page
                window.location.href = this.getAttribute('data-href');
            }
        });
    });
});



