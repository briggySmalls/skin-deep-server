/**
 * Simple script to add custom HTML to the cart
 */
/*global Snipcart*/
(function($) {
  // Wait for snipcart script to have loaded
  $('#snipcart').load(() => {
    console.assert(typeof Snipcart !== 'undefined', "Snipcart not found");
    Snipcart.subscribe('cart.opened', function() {
      // Only add the content once
      Snipcart.unsubscribe('cart.opened');

      // Append custom HTML content to cart
      var html = $("#cart-content-text").html();
      $(html).insertAfter($("#snipcart-header"));
    });

    // Close cart when logged out
    Snipcart.subscribe('user.loggedout', function() {
      Snipcart.api.modal.close();
    });
  });
})(jQuery)
