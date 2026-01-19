const main = document.querySelector("main");
main.querySelector("#logout-button").addEventListener("click", function(e){
    e.preventDefault();
    console.log("Logout initiated");
    fetch("../PHPUtilities/api-logout.php", { redirect: 'manual' })
    .then(response => {
        if (response.type === 'opaqueredirect' || response.status === 302 || response.status === 301) {
            // Redirect detected - go to login page
            window.location.href = response.headers.get('location');
        } else if (response.ok) {
            return response.json();
        }
    })
    .then(data => {
        if (data) console.log(data['error']);
    })
    .catch(error => console.error('Error:', error));
});