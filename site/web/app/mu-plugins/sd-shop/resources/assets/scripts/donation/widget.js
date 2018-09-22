import './../../styles/donation/widget.scss';

class BuyButton {
  constructor(button) {
    // Record the button instance
    this.button = button;
  }

  findAmount() {
    var inputGroup = this.button.closest('.input-group');
    var amountEls = inputGroup.getElementsByClassName('donate-amount');
    console.assert(amountEls.length === 1);
    var amount = amountEls[0].value;
    console.log("Amount is " + amount);
    return amount;
  }

  update() {
    var amount = this.findAmount();
    // Update the button price
    this.button.setAttribute('data-item-price', amount);
    // Update the button URL (used to validate)
    var url = new URL(document.location);
    url.searchParams.set('donation', amount);
    this.button.setAttribute('data-item-url', url);
  }

  static handleClick(event) {
    console.assert(event.type == 'click');
    console.log("clicked!");
    var button = new BuyButton(event.target);
    button.update();
  }
}

// Find all donation buttons, and register a handler
document.addEventListener("DOMContentLoaded", function() {
  var donation_buttons = document.getElementsByClassName('donate-button');
  [].forEach.call(donation_buttons, function(button) {
    button.addEventListener("click", BuyButton.handleClick);
  });
});
