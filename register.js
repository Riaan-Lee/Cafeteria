document.querySelector('form').onsubmit = function(e) {
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const cpassword = document.getElementById('cpassword').value;
    const messageContainer = document.querySelector('.form-message');

    if (!username || !email || !password || !cpassword) {
        messageContainer.innerHTML = "Please fill in all fields.";
        messageContainer.style.color = "red";
        e.preventDefault();
    } else if (password !== cpassword) {
        messageContainer.innerHTML = "Passwords do not match.";
        messageContainer.style.color = "red";
        e.preventDefault();
    } else if (password.length < 8) {
        messageContainer.innerHTML = "Password should be at least 8 characters.";
        messageContainer.style.color = "red";
        e.preventDefault();
    }
};
