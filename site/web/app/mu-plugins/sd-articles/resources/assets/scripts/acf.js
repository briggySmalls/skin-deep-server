/*global acf wp*/

const FEATURED_VIDEO_FIELD = 'field_5b21a633286bf';

// Configure featured video to hide/show on format
function isformatChanged() {
    let previousValue = currentValue;
    var currentValue = wp.data.select('core/editor').getEditedPostAttribute('format');
    if (currentValue != previousValue) {
        return true;
    }
    return false;
}

wp.data.subscribe(() => {
  if (isformatChanged()) {
    // Get the current value
    var format = wp.data.select('core/editor').getEditedPostAttribute('format');
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
