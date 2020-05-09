import './../../../styles/donation/public.scss';

class BuyButton {
  constructor(button) {
    // Record the button instance
    this.button = button;
  }

  /**
   * @brief      Determine the donation amount supplied
   * @return     The amount (in GBP)
   */
  findAmount() {
    var amountEls = this.button.closest('form').getElementsByClassName('donate-amount');
    console.assert(amountEls.length === 1);
    var amount = amountEls[0].value;
    console.log("Amount is " + amount);
    return amount;
  }

  /**
   * @brief      Update the button's properties based on value input
   */
  update() {
    var amount = this.findAmount();
    // Update the button price
    this.button.setAttribute('data-item-price', amount);
    // Update the button URL (used to validate)
    var url = new URL(document.location);
    url.hash = ''; // Remove any unnecessary anchor
    url.searchParams.set('donation', amount * 100); // Add donation parameter
    this.button.setAttribute('data-item-url', url);
  }

  /**
   * @brief      Handler for a click event on buy button
   * @param      event  The event
   */
  static handleClick(event) {
    console.assert(event.type == 'click');
    console.log("clicked!");
    var button = new BuyButton(event.target);
    button.update();
  }
}

// Find all donation buttons, and register a handler
document.addEventListener("DOMContentLoaded", function() {
  // Configure every buy button to update prior to handling
  var donation_buttons = document.getElementsByClassName('donate-button');
  [].forEach.call(donation_buttons, function(button) {
    button.addEventListener("click", function(event) {
      // First validate the form
      var form = button.closest('form');
      if (form.checkValidity() === false) {
        // The form validation failed, so stop there
        event.preventDefault();
        event.stopPropagation();
      } else {
        // Validation succeeded, so handle the click
        BuyButton.handleClick(event);
      }
      form.classList.add('was-validated');
    });
  });
}, false);
