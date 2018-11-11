// Import then needed Font Awesome functionality
import { library, dom } from '@fortawesome/fontawesome-svg-core';
// Import the required icons
import { faMapMarkerAlt } from "@fortawesome/free-solid-svg-icons";
import { faClock, faCalendar } from "@fortawesome/free-regular-svg-icons";
// Import brand icons
import { faFacebookF } from "@fortawesome/free-brands-svg-icons";

// Add the imported icons to the library
library.add(faClock, faCalendar, faMapMarkerAlt, faFacebookF);

// Tell FontAwesome to watch the DOM and add the SVGs when it detects icon markup
dom.watch();
