const main = document.querySelector("main");
console.log(main);
main.querySelector("#joinedEvents").addEventListener("click", function(e){
    e.preventDefault();
    console.log("Navigating to Joined Events");
    main.querySelector("#publishedEvents").classList.remove("selected");
    main.querySelector("#publishedEvents").classList.add("notSelected");
    main.querySelector("#joinedEvents").classList.add("selected");
    main.querySelector("#joinedEvents").classList.remove("notSelected");
    main.querySelector("#joinedEventsList").classList.remove("d-none");
    main.querySelector("#publishedEventsList").classList.add("d-none");
});

main.querySelector("#publishedEvents").addEventListener("click", function(e){
    e.preventDefault();
    console.log("Navigating to Published Events");
    main.querySelector("#publishedEvents").classList.remove("notSelected");
    main.querySelector("#publishedEvents").classList.add("selected");
    main.querySelector("#joinedEvents").classList.add("notSelected");
    main.querySelector("#joinedEvents").classList.remove("selected");
    main.querySelector("#joinedEventsList").classList.add("d-none");
    main.querySelector("#publishedEventsList").classList.remove("d-none");
});