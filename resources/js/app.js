import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { VueReCaptcha } from 'vue-recaptcha-v3';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(VueReCaptcha, {
                siteKey: import.meta.env.VITE_RECAPTCHA_SITE_KEY,
                loaderOptions: {
                    autoHideBadge: true
                }
            })
            .use(plugin)
            .mount(el)
    },
})