import './../../../styles/slider/public.scss';
import 'bcswipe'; // jQuery plugin

// Add swipe to carousels
jQuery(document).ready(function() {
  jQuery('.posts-slider.carousel').bcSwipe({threshold: 50});
});
