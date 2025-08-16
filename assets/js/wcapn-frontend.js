(function(){
  function ready(fn){ if(document.readyState!='loading'){ fn(); } else { document.addEventListener('DOMContentLoaded', fn); } }

  ready(function(){
    if (!window.wcapnData || !wcapnData.firebaseConfig || !wcapnData.vapidKey) {
      console.warn('WCAPN: Missing Firebase config or VAPID key.');
      return;
    }

    try {
      firebase.initializeApp(wcapnData.firebaseConfig);
    } catch(e) {
      // ignore if already initialized
    }
    const messaging = firebase.messaging();

    if (!('Notification' in window)) return;

    // Register service worker at site root for widest scope
    navigator.serviceWorker.register(wcapnData.swUrl).then(function(reg){
      // Request permission flow
      Notification.requestPermission().then(function(permission){
        if (permission === 'granted') {
          messaging.getToken({ vapidKey: wcapnData.vapidKey, serviceWorkerRegistration: reg })
          .then(function(token){
            if (token) {
              // Save token via AJAX
              jQuery.post(wcapnData.ajaxUrl, { action: 'wcapn_save_token', token: token });
            }
          }).catch(function(err){
            console.error('WCAPN token error:', err);
          });
        }
      });
    }).catch(function(err){
      console.error('WCAPN SW registration failed:', err);
    });

    // Optional: foreground message handler to show a notification
    messaging.onMessage(function(payload){
      if (payload && payload.notification) {
        const n = payload.notification;
        if (Notification.permission === 'granted') {
          const note = new Notification(n.title || 'WooCommerce', {
            body: n.body || '',
            icon: n.icon || ''
          });
          if (n.click_action) {
            note.onclick = function(){ window.open(n.click_action, '_blank'); };
          }
        }
      }
    });
  });
})();