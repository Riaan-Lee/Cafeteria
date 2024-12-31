document.querySelectorAll('.cart-btn').forEach(button => {
    button.addEventListener('click', () => {
        const menuItemId = button.getAttribute('data-id');
        
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `menu_item_id=${menuItemId}`
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.status === 'success' ? 'success' : 'error');
        });
    });
});

function showToast(message, type) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';

    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}
document.addEventListener('DOMContentLoaded', () => {
    const cartButtons = document.querySelectorAll('.cart-btn');
    const toast = document.getElementById('toast');

    // Event listener for "Add to Cart" buttons
    cartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;

            // Simulate adding item to the cart in sessionStorage
            let cart = JSON.parse(sessionStorage.getItem('cart')) || [];
            
            // Check if the item already exists in the cart
            const itemIndex = cart.findIndex(item => item.id === itemId);
            if (itemIndex !== -1) {
                // Increase the quantity of the existing item
                cart[itemIndex].quantity += 1;
            } else {
                // Add new item to the cart
                cart.push({ id: itemId, quantity: 1 });
            }

            // Save the updated cart to sessionStorage
            sessionStorage.setItem('cart', JSON.stringify(cart));

            // Display toast message
            toast.textContent = 'Item added to cart!';
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        });
    });
});
