/**
 * Simple script to add custom HTML to the cart
 */
/*global Snipcart*/

let $ = jQuery;

// Wait for snipcart script to have loaded
$('#snipcart').load(() => {
  console.assert(typeof Snipcart !== 'undefined', "Snipcart not found");
  // Add custom HTML to cart
  Snipcart.subscribe('cart.opened', function() {
    // Only add the content once
    Snipcart.unsubscribe('cart.opened');

    // Append custom HTML content to cart
    let html = $("#cart-content-text").html();
    $(html).insertAfter($("#snipcart-header"));

  });

  Snipcart.subscribe('cart.ready', function () {
    // Fix external link
    $('#snipcart-footer a[target="_blank"]').each(function () {
      let currentRel = $(this).attr('rel');
      currentRel = ensureStringPresent(currentRel, 'noopener');
      currentRel = ensureStringPresent(currentRel, 'noreferrer');
      currentRel = ensureStringPresent(currentRel, 'nofollow');
      $(this).attr('rel', currentRel);
    })
  });

  // Close cart when logged out
  Snipcart.subscribe('user.loggedout', function() {
    Snipcart.api.modal.close();
  });
});

function ensureStringPresent(current, query) {
  if (!current.includes(query)) {
    return current.concat(' ', query);
  }
  return current;
}
