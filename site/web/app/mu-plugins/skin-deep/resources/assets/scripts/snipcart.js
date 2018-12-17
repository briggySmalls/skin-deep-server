/**
 * Simple script to add custom HTML to the cart
 * @note       This script is loaded with async, after snipcart
 */
/*global Snipcart*/
jQuery('#snipcart').load(() => {
  console.assert(typeof Snipcart !== 'undefined', "Snipcart not found");
  Snipcart.execute('bind', 'cart.opened', function() {
      // Immediately unbind this event
      Snipcart.execute('unbind', 'cart.opened');

      // Append custom HTML content to cart
      var html = jQuery("#cart-content-text").html();
      jQuery(html).insertAfter(jQuery("#snipcart-header"));

      // Close cart when logged out
      Snipcart.subscribe('user.loggedout', function() {
        Snipcart.api.modal.close();
      });
  });
})
