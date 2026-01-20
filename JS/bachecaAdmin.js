function mostraEventiDaRisolvere() {
    document.getElementById("Div-Segnalazioni").classList.remove("d-none");
    document.getElementById("Div-Accettazioni").classList.add("d-none");
    document.getElementById("bottone-Segnalazione").classList.remove("notSelected");
    document.getElementById("bottone-Segnalazione").classList.add("selected");
    document.getElementById("bottone-Accettazione").classList.remove("selected");
    document.getElementById("bottone-Accettazione").classList.add("notSelected");
    
    history.pushState(null, '', '?sezione=segnalazioni');
}

function mostraEventiDaAccettare() {
    document.getElementById("Div-Segnalazioni").classList.add("d-none");
    document.getElementById("Div-Accettazioni").classList.remove("d-none");
    document.getElementById("bottone-Segnalazione").classList.remove("selected");
    document.getElementById("bottone-Segnalazione").classList.add("notSelected");
    document.getElementById("bottone-Accettazione").classList.remove("notSelected");
    document.getElementById("bottone-Accettazione").classList.add("selected");
    
    history.pushState(null, '', '?sezione=accettazioni');
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sezione = urlParams.get('sezione');
    
    if (sezione === 'segnalazioni') {
        mostraEventiDaRisolvere();
    } else {
        mostraEventiDaAccettare();
    }
});