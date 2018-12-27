import storage from 'local-storage-fallback'

export default {
  init() {
    // JavaScript to be fired on all pages

    // Remove 'shares' count from facebook buttons
    $('.fb-share-button').each(function() {
      $(this).attr('data-layout', 'button');
    });

    // Listen for close events on mailing list form
    $('#mailing-list').on('closed.bs.alert',function() {
      // Remember that the user has dismissed the alert
      storage.setItem('mailingListDismissed', true);
    })

    // Hide/show mailing list based on storage value
    if (!storage.getItem('mailingListDismissed')) {
      $('#mailing-list').show();
    }
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
