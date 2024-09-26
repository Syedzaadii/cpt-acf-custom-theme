import $ from "jquery";

class Search {
  // initiate
  constructor() {
    this.searchForm();
    this.openBtn = $(".js-search-trigger");
    this.closeBtn = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchInput = $(".search-term");
    this.resultDiv = $(".search-overlay__results");
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.typingTimer;
    this.previousValue;
    this.events();
  }
  // events
  events() {
    this.openBtn.on("click", this.openOverlay.bind(this));
    this.closeBtn.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressed.bind(this));
    this.searchInput.on("keyup", this.seaching.bind(this));
  }

  // methods
  seaching() {
    if (this.searchInput.val() != this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchInput.val()) {
        if (!this.isSpinnerVisible) {
          this.resultDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }

        this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
      } else {
        this.resultDiv.html(" ");
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchInput.val();
  }

  getResults() {
    $.getJSON(
      fictionalData.root_url +
        "/wp-json/fictional/v1/search?term=" +
        this.searchInput.val(),
      (data) => {
        this.resultDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title"> General Information </h2>
            ${
              data.general.length
                ? `<ul class="link-list min-list">`
                : "<p>Sorry! Not Found.</p>"
            }
              ${data.general
                .map(
                  (item) =>
                    `<li><a href="${item.link}">${item.title}</a>  ${
                      item.type == "post" ? `By ${item.authorName}` : ""
                    }</li>`
                )
                .join("")}

            ${data.general.length ? `</ul>` : ""} 
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title"> Program  </h2>
            ${
              data.program.length
                ? `<ul class="link-list min-list">`
                : "<p>Sorry! Not Found.</p>"
            }
              ${data.program
                .map(
                  (item) => `<li><a href="${item.link}">${item.title}</a> </li>`
                )
                .join("")}  

            ${data.program.length ? `</ul>` : ""} 
            <h2 class="search-overlay__section-title"> Professor  </h2>
            ${
              data.professor.length
                ? `<ul class="professor-cards">`
                : "<p>Sorry! Not Found.</p>"
            }
              ${data.professor
                .map(
                  (item) =>
                    `<li class="professor-card__list-item">
                        <a href="${item.link}" class="professor-card">
                            <img src="${item.image}" alt="" class="professor-card__image">
                            <span class="professor-card__name">${item.title}</span> 
                        </a>
                    </li>`
                )
                .join("")}

            ${data.professor.length ? `</ul>` : ""} 
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title"> Event  </h2>
            ${data.event.length ? `<div class="">` : "<p>Sorry! Not Found.</p>"}
              ${data.event
                .map(
                  (item) => `
                  <div class="event-summary">
                    <a class="event-summary__date t-center" href="${item.link}">
                        <span class="event-summary__month">${item.month}</span>
                        <span class="event-summary__day">${item.date}</span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a
                                href="${item.link}">${item.title}</a></h5>
                        <p> ${item.content} <a href="${item.link}" class="nu gray">Learn more</a></p>
                    </div>
                  </div>
                  `
                )
                .join("")}  

            ${data.event.length ? `</div>` : ""} 
            
            
            <h2 class="search-overlay__section-title"> Campus  </h2>
            ${
              data.campus.length
                ? `<ul class="link-list min-list">`
                : "<p>Sorry! Not Found.</p>"
            }
              ${data.campus
                .map(
                  (item) =>
                    `<li><a href="${item.link}">${item.title}</a>  ${
                      item.type == "post" ? `By ${item.authorName}` : ""
                    }</li>`
                )
                .join("")}

            ${data.campus.length ? `</ul>` : ""} 
          </div>
        </div>

      `);
        this.isSpinnerVisible = false;
      },
      () => {
        this.resultDiv.html("<p>Unexpected Error.</p>");
      }
    );
  }

  keyPressed(e) {
    if (
      e.keyCode == "83" &&
      !this.isOverlayOpen &&
      !$("input, textarea").is(":focus")
    ) {
      this.openOverlay();
    }
    if (e.keyCode == "27" && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.searchInput.val("");
    setTimeout(() => {
      this.searchInput.focus();
    }, 301);
    this.isOverlayOpen = true;
    return false;
  }
  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
  }

  searchForm() {
    $("body").append(`<div class="search-overlay">
    <div class="search-overlay__top">
        <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" name="" class="search-term" placeholder="What are you looking for?" id="search-term"
                autocomplete="off">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>

      </div>
      <div class="container">
          <div class="search-overlay__results"></div>
      </div>
    </div>`);
  }
}
export default Search;
