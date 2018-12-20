export default {
  init() {
    // JavaScript to be fired on all pages
    $('.fb-share-button').each(function() {
      // Remove 'shares' count from facebook buttons
      $(this).attr('data-layout', 'button');
    });
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
