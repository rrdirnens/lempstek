require('./bootstrap');

window.addEventListener( 'load', function() {
    console.log('js working')

    // profile menu script
    const profileMenu = document.querySelector('.profile-menu');
    const profileMenuBtn = document.querySelector('.profile-btn');
    profileMenuBtn.addEventListener('click', function() {
        console.log('clicked');
        profileMenu.classList.toggle('active');
    });
    // remove active class from profile menu when clicked outside
    document.addEventListener('click', function(e) {
        if (e.target.closest('.profile-menu') || e.target.closest('.profile-btn')) return;
        profileMenu.classList.remove('active');
    });

    // tabs script
    triggers = document.querySelectorAll('.tab-a');
    triggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var content = document.querySelector('.tab[data-id="' + id + '"]');
            var activeTabs = document.querySelectorAll('.tab-active');
            var activeTriggers = document.querySelectorAll('.active-a');

            activeTabs.forEach(function(activeTab) {
                activeTab.classList.remove('tab-active');
            });
            activeTriggers.forEach(function(activeTrigger) {
                activeTrigger.classList.remove('active-a');
            });

            trigger.classList.add('active-a');
            
            content.classList.add('tab-active');
        });
    });

    // search results column hover script
    const searchResults = document.querySelector('.search-results__container--desktop .search-results');
    const searchResultShowsCol = document.querySelector('.search-results__container--desktop .search-results__shows');
    const searchResultMoviesCol = document.querySelector(' .search-results__container--desktop .search-results__movies');

    if(searchResults) {
        searchResultMoviesCol.addEventListener('mouseover', function(e) {
            searchResultMoviesCol.classList.add('search-results__movies--hover');
            searchResultMoviesCol.classList.remove('search-results__movies--faded');
            searchResultShowsCol.classList.add('search-results__shows--faded');
            searchResultShowsCol.classList.remove('search-results__shows--hover');
        });
        searchResultShowsCol.addEventListener('mouseover', function(e) {
            searchResultShowsCol.classList.add('search-results__shows--hover');
            searchResultShowsCol.classList.remove('search-results__shows--faded');
            searchResultMoviesCol.classList.add('search-results__movies--faded');
            searchResultMoviesCol.classList.remove('search-results__movies--hover');
        });
        searchResults.addEventListener('mouseleave', function(e) {
            searchResultShowsCol.classList.remove('search-results__shows--hover');
            searchResultShowsCol.classList.remove('search-results__shows--faded');
            searchResultMoviesCol.classList.remove('search-results__movies--hover');
            searchResultMoviesCol.classList.remove('search-results__movies--faded');
        });
    }
})

