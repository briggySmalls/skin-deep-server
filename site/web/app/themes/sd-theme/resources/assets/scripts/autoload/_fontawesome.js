// Import then needed Font Awesome functionality
import { library, dom } from '@fortawesome/fontawesome-svg-core';
// Import the required icons
import { faClock, faCalendarAlt, faMapMarkerAlt, faVideo } from "@fortawesome/free-solid-svg-icons";
// Import brand icons
import { faFacebookF } from "@fortawesome/free-brands-svg-icons";

// Add the imported icons to the library
library.add(faClock, faCalendarAlt, faMapMarkerAlt, faFacebookF, faVideo);

// Tell FontAwesome to watch the DOM and add the SVGs when it detects icon markup
dom.watch();
