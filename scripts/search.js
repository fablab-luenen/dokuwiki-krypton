// Prevent empty search popup from showing up on load
let searchPopup = document.querySelector("#qsearch__out");
searchPopup.style.display = "none";

// Hide browser suggestions from search bar that overlap the search suggestions
let searchBar = document.querySelector("#qsearch__in");
searchBar.autocomplete = "off";


/////// Common
var searchPopupResults;
const searchPopupObserver = new MutationObserver((event) => {
    searchPopupResults = Array.from(document.querySelectorAll("#qsearch__out ul li a"));
    highlightActiveResult(activeResultId);
});
searchPopupObserver.observe(searchPopup, {childList: true});

/////// Keyboard navigation
var activeResultId;

/** Get the DOM element of an active result from its id */
function getActiveResult() {
    return searchPopupResults.find(e => e.attributes["data-wiki-id"].value == activeResultId);
}

/** Make the selected result visibly active */
function highlightActiveResult(activeResultId) {
    let activeClass = "active";
    let activeResult = getActiveResult(activeResultId);
    if(!activeResult) return;

    searchPopupResults.forEach(e => e.classList.remove(activeClass)); // Clear other results
    activeResult.classList.add(activeClass);
}

searchBar.addEventListener("keydown", function(event){
    if(!searchPopupResults) return; // List has not loaded yet

    let activeResult = getActiveResult(activeResultId);
    let activeIndex = searchPopupResults.findIndex(e => e == activeResult);

    switch (event.key) {
        case "ArrowDown":
            activeIndex++;
            break;
        case "ArrowUp":
            activeIndex--;
            break;
        case "Enter":
            // Navigate there
            activeResult?.click();
            break;
    }

    if(activeIndex > searchPopupResults.length-1) { activeIndex = 0; }
    if(activeIndex < 0) { activeIndex = searchPopupResults.length-1; } 

    activeResultId = searchPopupResults[activeIndex].attributes["data-wiki-id"].value;
    highlightActiveResult(activeResultId);
});