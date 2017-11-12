window.Vue = require('vue');

window.$ = window.jQuery = require('jquery');

require('!style-loader!css-loader!less-loader!z-ui/less/bootstrap.less');
require('!style-loader!css-loader!less-loader!../less/layout.less');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.globalFormOptions = {
	texts: {
		validationFailed: 'Deine eingegebenen Daten sind leider fehlerhaft.',
		sending: 'Wird gesendet...'
	}
};

import InputMask from 'vue-inputmask';
Vue.use(InputMask);

require('font-awesome-webpack');
