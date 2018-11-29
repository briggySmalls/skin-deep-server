/*global Snipcart*/
export default {
  init() {
    // JavaScript to be fired on all pages
    Snipcart.execute('bind', 'cart.opened', function() {
        // Immediately unbind this event
        Snipcart.execute('unbind', 'cart.opened');

        // Append custom HTML content to cart
        var html = $("#cart-content-text").html();
        $(html).insertAfter($("#snipcart-header"));

        // Close cart when logged out
        Snipcart.subscribe('user.loggedout', function () {
          Snipcart.api.modal.close();
        });
    });
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
