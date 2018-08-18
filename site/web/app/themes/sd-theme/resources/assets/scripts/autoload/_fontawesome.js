// Import then needed Font Awesome functionality
import { library, dom } from '@fortawesome/fontawesome-svg-core';
// Import the required icons
import { faClock, faCalendarAlt, faMapMarkerAlt } from "@fortawesome/free-solid-svg-icons";
// Import brand icons
import { faFacebookF } from "@fortawesome/free-brands-svg-icons";

// Add the imported icons to the library
library.add(faClock, faCalendarAlt, faMapMarkerAlt, faFacebookF);

// Tell FontAwesome to watch the DOM and add the SVGs when it detects icon markup
console.log("Font awesome loaded");
dom.watch();
