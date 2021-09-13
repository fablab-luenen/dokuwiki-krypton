// Prevent empty search popup from showing up on load
let searchPopup = document.querySelector("#qsearch__out");
searchPopup.style.display = "none";

// Hide browser suggestions from search bar that overlap the search suggestions
let searchBar = document.querySelector("#qsearch__in");
searchBar.autocomplete = "off";