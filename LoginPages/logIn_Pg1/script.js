
document.getElementById("loginForm").addEventListener("submit", function(event) {
event.preventDefault(); // Prevent form submission
 
    // Get user input
const email = document.querySelector(".email").value;
const password = document.querySelector(".password").value;
  
    // Validate email and password
    if (email === "nakae@gmail.com" && password === "admin1") {
      // Redirect to Dashboard page
      window.location.href = "dashboard.html";
    }else if (email === "user1@gmail.com" && password === "user123") {
        // Redirect to User Dashboard page
        window.location.href = "user-dashboard.html";
    }
        else {
      alert("Invalid email or password. Please try again.");
    }
  });

  // JavaScript for Show Password
document.querySelector(".showPassword").addEventListener("change", function() {
    const passwordInput = document.querySelector(".password");
    if (this.checked) {
      passwordInput.type = "text";
    } else {
      passwordInput.type = "password";
    }
  });

  function signOut() {
    // Redirect to login page
    window.location.href = "index.html"
  };
  