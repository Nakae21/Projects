

window.onload = function() {
    const gallery = document.getElementById('gallery');
    for (let i = 1; i <= 3; i++) {
        const img = document.createElement('img');
        img.src = `images/student${i}.jpg`; // Make sure images are named student1.jpg, student2.jpg, etc.
        img.alt = 'School photo';
        gallery.appendChild(img);
    }
};


//Administion javascripts
function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const message = document.getElementById('loginMessage');
    const allowedUsernames = ['huniadmin']; // List of usernames that are allowed to log in

    if (allowedUsernames.includes(username) && password === "passnakae") {
        message.innerText = "Login successful!";
        message.style.color = "green";
    } else {
        message.innerText = "Invalid username or password!";
        message.style.color = "red";
    }
}


//Contact javascripts
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('messageForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent the default form submission which causes a page reload

        // Fetch all the inputs from the form
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const message = document.getElementById('message').value;

        // Simple validation to ensure all fields are filled
        if (name.trim() === '' || email.trim() === '' || message.trim() === '') {
            alert('Please fill in all fields.');
            return;
        }

        // Further validations can be added here, e.g., email format check
        if (!validateEmail(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        // If everything is okay, show a success message
        alert('Thank you for your message! We will get back to you soon.');

        // Optionally, clear the form or do something else upon successful submission
        form.reset();  // Reset the form fields
    });

    // Function to validate email using regex
    function validateEmail(email) {
        const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(String(email).toLowerCase());
    }
});

//Admission javascripts
document.getElementById('admissionForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    const files = document.querySelector('[type=file]').files;
    if (files.length === 0) {
        alert('Please attach the required documents.');
        return;
    }
    alert('Thank you for submitting your application with all required documents. We will review it and get back to you shortly.');
    this.reset(); // Optionally reset the form fields
});

