/*global acf wp*/

const FEATURED_VIDEO_FIELD = 'field_5b21a633286bf';

// Listen for data changes
jQuery(document).ready(function () {
  if ((typeof wp === 'object') && (wp.hasOwnProperty('data'))) {
    // We are in the Gutenberg editor
    wp.data.subscribe(() => {
      if (isformatChanged()) {
        // Get the current value
        var format = getCurrentFormat();
        // Get the featured video ACF field
        var featured_video_field = acf.getField(FEATURED_VIDEO_FIELD);
        // Toggle its visibility
        if (format === 'video') {
          featured_video_field.show();
        } else {
          featured_video_field.hide();
        }
      }
    });

    // Validate that all videos have a video source
    acf.add_filter('validation_complete', function (json) {
      // Check if post has video if it's video format
      if ((getCurrentFormat() === 'video') && !acf.getField(FEATURED_VIDEO_FIELD).val()) {
        if (json.errors == 0) {
          json.errors = [];
        }
        json.errors.push({
          'input': `acf[${FEATURED_VIDEO_FIELD}]`,
          'message': 'Featured video required for video posts',
        });
        json.valid = 0;
        return json
      }
      return json;
    });
  }
});

/**
 * @brief      Helper function to get the current post format.
 * @return     The format.
 */
function getCurrentFormat() {
  return wp.data.select('core/editor').getEditedPostAttribute('format');
}

/**
 * @brief      Determine if format is different from previous value
 * @return     true if format has changed, otherwise false
 */
function isformatChanged() {
    let previousValue = currentValue;
    var currentValue = getCurrentFormat();
    if (currentValue != previousValue) {
        return true;
    }
    return false;
}
