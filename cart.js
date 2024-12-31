document.querySelectorAll('.decrease-btn').forEach(button => {
    button.addEventListener('click', () => {
        const cartId = button.dataset.id;
        updateQuantity(cartId, -1);
    });
});

document.querySelectorAll('.increase-btn').forEach(button => {
    button.addEventListener('click', () => {
        const cartId = button.dataset.id;
        updateQuantity(cartId, 1);
    });
});

document.querySelectorAll('.remove-btn').forEach(button => {
    button.addEventListener('click', () => {
        const cartId = button.dataset.id;
        removeItem(cartId);
    });
});

function updateQuantity(cartId, change) {
    // Add AJAX request to update the cart quantity in the database
    console.log(`Updating cart item ${cartId} with change ${change}`);
}

function removeItem(cartId) {
    // Add AJAX request to remove the item from the cart in the database
    console.log(`Removing cart item ${cartId}`);
}
