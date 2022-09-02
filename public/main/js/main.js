jQuery(
  (function ($) {
    "use strict";

    // Header Sticky
    // Popup Video
    $(".popup-youtube").magnificPopup({
      disableOn: 320,
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 160,
      preloader: false,
      fixedContentPos: false,
    });
  })(jQuery)
);
