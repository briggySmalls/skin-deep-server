// Run google analytics script
window.dataLayer = window.dataLayer || [];
function gtag(){window.dataLayer.push(arguments);}
gtag('js', new Date());

// Pass the tracking ID
var id = jQuery('#google-tag-manager-script').attr('data-google-tracking-id');
gtag('config', id);
