import Vue from 'vue';

import router from './router';
import store from './store';
import i18n from './i18n';
import Auth from './plugins/auth';

import app from './views/layouts/App';

import * as VueGoogleMaps from 'vue2-google-maps';
import {GOOGLE_API_KEY} from './views/profile/helpers/exports';

Vue.use(Auth, store, router);
Vue.use(VueGoogleMaps, {
    load: {
        key: GOOGLE_API_KEY,
        v: '3.26',
        libraries: 'places',
    },
});
Vue.component('b-form', require('./components/Form').default);
Vue.component('b-api', require('./components/BrainrApi').default);
Vue.component('date', require('./components/Date').default);

export default new Vue({
    router,
    store,
    i18n,
    components: {
        app,
    },
});
