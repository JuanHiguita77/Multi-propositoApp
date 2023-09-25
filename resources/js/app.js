import './bootstrap';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import {createApp} from 'vue/dist/vue.esm-bundler.js';
import { createPinia } from 'pinia';
import {createRouter, createWebHistory} from 'vue-router';
import Login from './pages/auth/Login.vue';
import Routes from './routes.js';
import App from './App.vue';

const app = createApp(App);

//Primer paso para hacer el cambio del nombre reactivo
const pinia = createPinia();

const router = createRouter({

	routes:Routes,
	history:createWebHistory(),
});

//Segundo paso para hacer el cambio del nombre reactivo: instlar pinia en este caso e importarlo
//Vamos a crear un nuevo archivo llamado AuthUserStore
app.use(pinia);

//Le indicamos que tipo de enrutador usaremos
app.use(router);


if (window.location.pathname === '/login')
{
	const currentApp = createApp({});

	//Cargamos la pagina de login
	currentApp.component('Login', Login);

	//Montamos en la pagina de login
	currentApp.mount('#login');
}
else 
{
	//Montamos la pagina principal
	app.mount('#app');
};

