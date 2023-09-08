<script setup>
	
	//Quinto paso de los settings: Creamos las funciones 
	import { onMounted, ref } from 'vue';
	//toastr para alertas bonitas
	import { useToastr } from '@/toastr';

	//Sexto paso: Referenciamos el campo usando vmodel
	const settings = ref([]);

	const toastr = useToastr();

	const getSettings = () =>
	{
		//septimo paso: hacemos la peticion a la base de datos por la informacion
		//Vamos a crear la ruta en web.php y su controlador
		axios.get('/api/settings')

		.then((response)=>
		{
			//El valor sera sacado de la data recibida desde la base de datos en la response, segun necesitemos
			settings.value = response.data;
		});
	}

	//Doceavo paso de los settings: configurar el boton de guardar los settings
	//Creamos la peticion para enviar los datos y vamos a crear su ruta en web
	const updateSettings = () =>
	{
		axios.post('/api/settings', settings.value)

		//Aqui llega la respuesta satisfactoria json del controlador de los settings
		.then((response) => 
		{
			//usamos una alerta que ya tenemos definida
			toastr.success('Settings Update Successfully');
		})
	}


	//Decimo paso: Montamos la app
	onMounted(()=>
	{
		getSettings();
	});

</script>

<template>
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Settings</h1>
				</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Settings</li>
				</ol>
			</div>
			</div>
		</div>	
	</div>		

	<!-- Primer paso para la parte de settings: Creamos el template -->
	<!-- Despues creamos el modelo en consola: 'php artisan make:model Setting -m' el cual estara en migraciones -->
	<div class="content">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-md-6">
	                <div class="card card-primary">
	                    <div class="card-header">
	                        <h3 class="card-title">General Setting</h3>
	                    </div>

	                    <form @submit.prevent='updateSettings()'>
	                        <div class="card-body">
	                            <div class="form-group">
	                                <label for="appName">App Display Name</label>
	                                <!-- Onceavo paso: Le pasamos el v-model para referir el campo -->
	                                <input v-model="settings.app_name" type="text" class="form-control" id="appName" placeholder="Enter app display name">
	                            </div>
	                            <div class="form-group">
	                                <label for="dateFormat">Date Format</label>
	                                <select v-model="settings.date_format" class="form-control">
	                                    <option value="MM/DD/YYYY">MM/DD/YYYY</option>
	                                    <option value="DD/MM/YYYY">DD/MM/YYYY</option>
	                                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
	                                    <option value="Month DD, YYYY">Month DD, YYYY</option>
	                                    <option value="DD Month YYYY">DD Month YYYY</option>
	                                </select>
	                            </div>
	                            <div class="form-group">
	                                <label for="paginationLimit">Pagination Limit</label>
	                                <input v-model="settings.pagination_limit" type="text" class="form-control" id="paginationLimit" placeholder="Enter pagination limit">
	                            </div>
	                        </div>

	                        <div class="card-footer">
	                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i>Save Changes</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

</template>

