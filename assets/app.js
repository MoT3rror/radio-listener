import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';

import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'dark',
    }
})

document.addEventListener('vue:before-mount', (event) => {
    const {
        app, // The Vue application instance
    } = event.detail;

    app.use(vuetify);
});

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));