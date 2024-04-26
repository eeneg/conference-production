import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.js';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        if(name == 'Welcome' || name == 'Auth/Login' || name == 'Auth/ForgotPassword' || name == 'Auth/Register'){
            page.then((module) => {
                module.default.layout = module.default.layout 
            });
        }else{
            page.then((module) => {
                module.default.layout = AuthenticatedLayout;
            });
        }
        return page
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    created(){
        window.Echo.channel(`chat`)
        .listen('App\\Events\\MessageSentEvent', (e) => {
            console.log(e);
        });
    },
    progress: {
        color: '#4B5563',
    },
});
