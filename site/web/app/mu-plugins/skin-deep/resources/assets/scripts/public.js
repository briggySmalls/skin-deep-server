// Run google analytics script
window.dataLayer = window.dataLayer || [];
function gtag(){window.dataLayer.push(arguments);}
gtag('js', new Date());

// Pass the tracking ID (substituted at compile time)
gtag('config', process.env.GOOGLE_TRACKING_ID);
