// Import then needed Font Awesome functionality
import { library, dom } from '@fortawesome/fontawesome-svg-core';
// Import the required icons
import { faSearch } from "@fortawesome/free-solid-svg-icons";

// Add the imported icons to the library
library.add(faSearch);

// Tell FontAwesome to watch the DOM and add the SVGs when it detects icon markup
dom.watch();
