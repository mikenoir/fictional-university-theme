import $ from 'jquery';

class Search {

    // 1. describe and create/initiate our object
    constructor() {
        this.openButton = $('.js-search-trigger');
        this.closeButton = $('.search-overlay__close');
        this.searchOverlay = $('.search-overlay');
        this.isOverlayOpen = false;
        this.searchField = $('#search-term');
        this.typingTimer;
        this.resultsDiv = $('#search-overlay__results');
        this.isSpinnerVisible = false;
        this.previousValue;
        this.events();
    }

    // 2. Events
    events() {
        this.openButton.on('click', this.openOverlay.bind(this));
        this.closeButton.on('click', this.closeOverlay.bind(this));
        $(document).on('keydown', this.keyPressDispatcher.bind(this));
        this.searchField.on('keyup', this.typingLogic.bind(this));
    }

    // 3. Methods

    typingLogic() {

        if (this.previousValue === this.searchField.val()) {
            return;
        }

        clearTimeout(this.typingTimer);

        if (this.searchField.val()) {
            if (!this.isSpinnerVisible) {
                this.resultsDiv.html('<div class="spinner-loader"></div>');
                this.isSpinnerVisible = true;
            }
            this.typingTimer = setTimeout(this.getResults.bind(this), 750);
        } else {
            this.resultsDiv.html('');
            this.isSpinnerVisible = false;
        }

        this.previousValue = this.searchField.val();
    }

    getResults() {

        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.generalInfo.length ? `
                            <ul class="link-list min-list">
                                ${results.generalInfo.map(item =>
                                    `<li>
                                        <a href="${item.link}">${item.title}</a>
                                        ${item.postType === 'post' ? ` by ${item.authorName}` : ''} 
                                    </li>`
                                ).join('')}
                            </ul>` :
                            `<p>No general information matches that search.</p>`
                        }
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.program.length ? `
                            <ul class="link-list min-list">
                                ${results.program.map(item =>
                                    `<li>
                                        <a href="${item.link}">${item.title}</a>
                                    </li>`
                                ).join('')}
                            </ul>` :
                            `<p>No programs matches that search. <a href="${universityData.root_url}/programs">View all programs.</a></p>`
                        }
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${results.professor.length ? `
                            <ul class="professor-cards">
                                ${results.professor.map(item => `
                                    <li class="professor-card__list-item">
                                        <a class="professor-card" href="${item.permalink}">
                                            <img class="professor-card__image" src="${item.thumbnail}" alt="">
                                            <span class="professor-card__name">${item.title}</span>
                                        </a>
                                    </li>`
                                ).join('')}
                            </ul>` :
                            `<p>No professors matches that search.</p>`
                        }
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${results.campus.length ? `
                            <ul class="link-list min-list">
                                ${results.campus.map(item =>
                                    `<li>
                                        <a href="${item.link}">${item.title}</a>
                                    </li>`
                                ).join('')}
                            </ul>` :
                            `<p>No campuses matches that search. <a href="${universityData.root_url}/campuses">View all campuses.</a></p>`
                        }
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${results.event.length ? `
                                ${results.event.map(item =>
                                    `<div class="event-summary">
                                        <a class="event-summary__date t-center" href="${item.permalink}">
                                            <span class="event-summary__month">
                                                ${item.month}
                                            </span>
                                            <span class="event-summary__day">
                                                ${item.day}
                                            </span>
                                        </a>
                                        <div class="event-summary__content">
                                            <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                                            <p>${item.content} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                                        </div>
                                    </div>`
                                ).join('')}
                            ` :
                            `<p>No events matches that search. <a href="${universityData.root_url}/campuses">View all events.</a></p>`
                        }
                    </div>
                </div>
            `);

            this.isSpinnerVisible = false;
        });
    }

    openOverlay() {

        if (this.isOverlayOpen) {
            return;
        }

        this.searchOverlay.addClass('search-overlay--active');
        $('body').addClass('body-no-scroll');
        this.searchField.focus();
        this.isOverlayOpen = true;
    }

    closeOverlay() {

        if (!this.isOverlayOpen) {
            return;
        }

        this.searchOverlay.removeClass('search-overlay--active');
        $('body').removeClass('body-no-scroll');
        this.resultsDiv.html('');
        this.searchField.val('');
        this.isOverlayOpen = false;
    }

    keyPressDispatcher(e) {

        if (!this.isOverlayOpen && $('input, textarea').is(':focus')) {
            return;
        }

        const key = e.keyCode;

        if (key === 83 && !this.isOverlayOpen) {
            this.openOverlay();
        }

        if (key === 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }
}

export default Search;