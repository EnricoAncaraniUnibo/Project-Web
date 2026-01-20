const searchInput = document.querySelector('input[name="search"]');
let searchTimeout;

searchInput.addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        this.form.submit();
    }, 500); // Aspetta 500ms dopo che l'utente smette di digitare
});