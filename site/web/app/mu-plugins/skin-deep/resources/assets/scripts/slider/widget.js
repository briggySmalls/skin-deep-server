import './../../styles/slider/widget.scss';
import 'bcswipe/jquery.bcSwipe.js'; // jQuery plugin

// Add swipe to carousels
jQuery(document).ready(function() {
  jQuery('.posts-slider.carousel').bcSwipe({threshold: 50});
});
