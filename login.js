document.querySelector('form').onsubmit = function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const messageContainer = document.querySelector('.form-message');

    if (!email || !password) {
        messageContainer.innerHTML = "Please fill in all fields.";
        messageContainer.style.color = "red";
        e.preventDefault();
    }
};
