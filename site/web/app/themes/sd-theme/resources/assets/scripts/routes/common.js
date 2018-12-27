import store from 'store';
import expirePlugin from 'store/plugins/expire';

const MAILING_LIST_DISMISSED_TAG = 'mailing_list_dismissed';

// Add expire plugin to storage
store.addPlugin(expirePlugin);

export default {
  init() {
    // JavaScript to be fired on all pages

    // Remove 'shares' count from facebook buttons
    $('.fb-share-button').each(function() {
      $(this).attr('data-layout', 'button');
    });

    // Listen for close events on mailing list form
    $('#mailing-list').on('closed.bs.alert',function() {
      // Get a date one month from now
      var expiry = new Date();
      expiry.setMonth(expiry.getMonth() + 1);
      // Remember that the user has dismissed the alert
      store.set(MAILING_LIST_DISMISSED_TAG, true, expiry.getTime());
    })

    // Hide/show mailing list based on storage value
    if (!store.get(MAILING_LIST_DISMISSED_TAG)) {
      $('#mailing-list').show();
    }
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
