/*global acf wp*/

const FEATURED_VIDEO_FIELD = 'field_5b21a633286bf';

function getCurrentFormat() {
  return wp.data.select('core/editor').getEditedPostAttribute('format');
}

// Configure featured video to hide/show on format
function isformatChanged() {
    let previousValue = currentValue;
    var currentValue = getCurrentFormat();
    if (currentValue != previousValue) {
        return true;
    }
    return false;
}

// Listen for data changes
wp.data.subscribe(() => {
  if (isformatChanged()) {
    // Get the current value
    var format = getCurrentFormat();
    // Get the featured video ACF field
    var featured_video_field = acf.getField(FEATURED_VIDEO_FIELD);
    // Toggle it's visibility
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
