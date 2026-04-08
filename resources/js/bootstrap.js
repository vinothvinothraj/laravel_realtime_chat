import axios from 'axios';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const echoKey = import.meta.env.VITE_PUSHER_APP_KEY;

if (echoKey) {
    window.Pusher = Pusher;
    window.pusher = new Pusher(echoKey, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
    });

    Pusher.logToConsole = true;
}
