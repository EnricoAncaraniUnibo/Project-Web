function mostraEventiDaRisolvere() {
    document.getElementById("Div-Segnalazioni").classList.remove("d-none");
    document.getElementById("Div-Accettazioni").classList.add("d-none");
    document.getElementById("bottone-Segnalazione").classList.remove("notSelected");
    document.getElementById("bottone-Segnalazione").classList.add("selected");
    document.getElementById("bottone-Accettazione").classList.remove("selected");
    document.getElementById("bottone-Accettazione").classList.add("notSelected");
}

function mostraEventiDaAccettare() {
    document.getElementById("Div-Segnalazioni").classList.add("d-none");
    document.getElementById("Div-Accettazioni").classList.remove("d-none");
    document.getElementById("bottone-Segnalazione").classList.remove("selected");
    document.getElementById("bottone-Segnalazione").classList.add("notSelected");
    document.getElementById("bottone-Accettazione").classList.remove("notSelected");
    document.getElementById("bottone-Accettazione").classList.add("selected");
}