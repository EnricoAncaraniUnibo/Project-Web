const nav = document.querySelector("nav");
nav.querySelector("#logout-button").addEventListener("click", function(e){
    e.preventDefault();
    console.log("Logout initiated");
    
    fetch("../PHPUtilities/api-logout.php")
    .then(response => {
        // Se la risposta è ok o redirect, vai alla home
        if (response.ok || response.status === 302 || response.status === 301) {
            window.location.href = "../PHPPages/index.php";
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.error) {
            console.log(data['error']);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback redirect
        window.location.href = "../PHPPages/index.php";
    });
});