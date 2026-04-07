import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const echoKey = import.meta.env.VITE_PUSHER_APP_KEY;

if (echoKey) {
    window.Pusher = Pusher;

    Pusher.logToConsole = true;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: echoKey,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        encrypted: true,
        enabledTransports: ['ws', 'wss'],
    });
}
