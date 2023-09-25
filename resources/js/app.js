import './bootstrap';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import {createApp} from 'vue/dist/vue.esm-bundler.js';
import {createRouter, createWebHistory} from 'vue-router';
import Login from './pages/auth/Login.vue';
import Routes from './routes.js';
import App from './App.vue';

const app = createApp(App);

const router = createRouter({

	routes:Routes,
	history:createWebHistory(),
});

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

