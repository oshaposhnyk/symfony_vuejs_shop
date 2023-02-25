import { createApp } from 'vue';
import App from "./App.vue";
import store from './store/store';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { VSimpleTable } from 'vuetify/lib'

const app = createApp(App);
const vuetify = createVuetify({
    components: {
        ...components,
        VSimpleTable,
    },
    directives,
})

app.use(vuetify);
app.use(store);

app.mount('#app');
