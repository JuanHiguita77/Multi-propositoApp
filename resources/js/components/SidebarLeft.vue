<script setup>
	import { useAuthUserStore } from '../stores/AuthUserStore';

	const authUserStore = useAuthUserStore();

	defineProps({
		user: Object,
		settings: Object,
	});

</script>

<template>
			<aside class="main-sidebar sidebar-dark-primary elevation-4">

			<a href="index3.html" class="brand-link">
				<img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				<!-- Tercer paso para guardar la cache: Aqui ya usamos los nuevos datos para mostrarlos -->
				<!-- Tenemos que definir valores por default en caso de no tener cache, creamos nuevo archivo settings.php en config -->
				<span class="brand-text font-weight-light"> APP NAME </span>
			</a>
			<!-- {{settings.app_name}} -->

			<div class="sidebar">

				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img :src="user?.avatar" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<!-- Sacamos el nombre del usuario logeado en el momento -->
						<a href="#" class="d-block">{{ authUserStore.user.name }}</a>
					</div>
				</div>

				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

						<li class="nav-item">
							<router-link to="/admin/dashboard" active-class="active" class="nav-link">
								<i class="nav-icon fas fa-tachometer-alt"></i>
								<p>
									Dashboard
									<span class="right badge badge-danger">New</span>
								</p>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link to="/admin/appointments" :class="$route.path.startsWith('/admin/appointments') ? 'active' : ''" class="nav-link">
								<i class="nav-icon fas fa-calendar-alt"></i>
								<p>
									Appointments
									<span class="right badge badge-danger">New</span>
								</p>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link to="/admin/users" active-class="active" class="nav-link">
								<i class="nav-icon fas fa-users"></i>
								<p>
									Users
									<span class="right badge badge-danger">New</span>
								</p>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link to="/admin/settings" active-class="active" class="nav-link">
								<i class="nav-icon fas fa-cog"></i>
								<p>
									Settings
									<span class="right badge badge-danger">New</span>
								</p>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link to="/admin/profile" active-class="active" class="nav-link">
								<i class="nav-icon fas fa-user"></i>
								<p>
									Profile
									<span class="right badge badge-danger">New</span>
								</p>
							</router-link>
						</li>

						<li class="nav-item">
							<form class="nav-link">
								<a href="#" @click.prevent="logout">
									<i class="nav-icon fas fa-sign-out-alt"></i>
									<p>
										Logout
										<span class="right badge badge-danger">New</span>
									</p>
								</a>
							</form>
						</li>
					</ul>
				</nav>
			</div>
		</aside>
</template>