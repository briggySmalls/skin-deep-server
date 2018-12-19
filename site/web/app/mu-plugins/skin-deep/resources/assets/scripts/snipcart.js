/**
 * Simple script to add custom HTML to the cart
 */
/*global Snipcart*/
(function($) {
  $('#snipcart').load(() => {
    console.assert(typeof Snipcart !== 'undefined', "Snipcart not found");
    Snipcart.execute('bind', 'cart.opened', function() {
        // Immediately unbind this event
        Snipcart.execute('unbind', 'cart.opened');

        // Append custom HTML content to cart
        var html = $("#cart-content-text").html();
        $(html).insertAfter($("#snipcart-header"));

        // Close cart when logged out
        Snipcart.subscribe('user.loggedout', function() {
          Snipcart.api.modal.close();
        });
    });
  });
})(jQuery)
