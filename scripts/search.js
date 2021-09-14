function setupSearch() {
    console.log("Setting up search");

    // Prevent empty search popup from showing up on load
    let searchPopup = document.querySelector("#qsearch__out");
    searchPopup.style.display = "none";
    // Redo this for fake page load, since it reappears

    // Hide browser suggestions from search bar that overlap the search suggestions
    let searchBar = document.querySelector("#qsearch__in");
    searchBar.autocomplete = "off";

    /////// Common
    var searchPopupResults;
    var searchTerm;
    const searchPopupObserver = new MutationObserver((event) => {
        createPageItem();

        searchPopupResults = Array.from(document.querySelectorAll("#qsearch__out ul li a"));
        keyboardNavigation();
    });
    searchPopupObserver.observe(searchPopup, {childList: true});

    searchBar.addEventListener("keydown", function(event){
        keyboardNavigation(event);
    });

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

    function keyboardNavigation(event) {
        if(!searchPopupResults) return; // List has not loaded yet

        let activeResult = getActiveResult(activeResultId);

        let activeIndex;
        if(!event) {
            activeIndex = 0;
        } else { 
            activeIndex = searchPopupResults.findIndex(e => e == activeResult);
        }

        switch (event?.key) {
            case "ArrowDown":
                activeIndex++;
                event.preventDefault();
                break;
            case "ArrowUp":
                activeIndex--;
                event.preventDefault();
                break;
            case "Enter":
                // Navigate there
                event.preventDefault();
                activeResult?.click();
                break;
        }

        if(activeIndex > searchPopupResults.length-1) { activeIndex = 0; }
        if(activeIndex < 0) { activeIndex = searchPopupResults.length-1; } 

        activeResultId = searchPopupResults[activeIndex].attributes["data-wiki-id"].value;
        highlightActiveResult(activeResultId);
    }

    /////// Page creation
    function createPageItem() {
        console.log("results", searchPopupResults);
        if(!searchPopupResults) return;

        let pageName = searchBar.value;

        // Do not add this button if the page is already in the list or the query is empty
        let isPageNameInList = searchPopupResults.find(e => e.attributes["data-wiki-id"].value == pageName && !e.classList.contains("createButton"));
        if(pageName == "" || isPageNameInList) {
            return;
        }

        let addIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>';
        
        // We can't do anything on empty result lists yet, because no suggestion popup will show up. Might fix this in qsearch. 
        let resultsList = document.querySelector("#qsearch__out ul");
        resultsList?.insertAdjacentHTML("beforeend", `<li><a href="/${pageName}?do=edit" data-wiki-id="${pageName}" class="createButton" title="${LANG.template.krypton.createPage}">${addIcon} ${pageName} </a></li>`); 
    }

    // Replace search bar placeholder text
    searchBar.placeholder = LANG.template.krypton.searchOrCreatePage;

    console.log("Search set up");
}

window.addEventListener("DOMContentLoaded", setupSearch);