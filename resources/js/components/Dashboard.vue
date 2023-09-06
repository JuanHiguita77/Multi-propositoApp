<script setup>
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';

	const selectedAppointmentStatus = ref('all');	

	const totalAppointmentsCount = ref(0);

	//Obtenemos la cantidad de appointments
	const getAppointmentsCount = ()=>
	{
		axios.get('/api/stats/appointments', {
			params: {
				status: selectedAppointmentStatus.value,
			}
		})
		.then((response)=>
		{
			totalAppointmentsCount.value = response.data.totalAppointmentsCount;
		})
	}

	//Hacemos la referencia a cuando se seleccione una opcion y se la damos a la variable para que sepa el rango
	const selectedDateRange = ref('today');

	//Referenciamos el contador e iniciamos en cero
	const totalUsersCount = ref(0);


	//Segundo paso, creacion del metodo para contar usuarios
	const getUsersCount = ()=>
	{
		//hacemos la peticion de los datos de usuarios y extraemos el valor de la fecha de creacion
		//Tercer paso del contador de usuarios: Creamos la ruta en web.php
		axios.get('/api/stats/users', 
		{
			params: 
			{
				date_range: selectedDateRange.value,
			}
		})
		.then((response) =>
		{
			//Le asignamos el valor que nos da la respuesta como valor al contador de usuarios
			totalUsersCount.value = response.data.totalUsersCount;
		})
	}

	onMounted(()=>
	{
		getAppointmentsCount();

		//Quinto paso del contador de usuarios: Montamos el metodo para que este listo para ser ejecutado
		getUsersCount();
	});

</script>

<template>

<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Dashboard</h1>
			</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active">Dashboard</li>
			</ol>
		</div>
		</div>
	</div>	
</div>		


<div class="content">
	<div class="container-fluid">
		<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <div class="d-flex justify-content-between">
                    <h3>{{ totalAppointmentsCount }}</h3>
                    <select v-model="selectedAppointmentStatus" @change="getAppointmentsCount()" style="height: 2rem; outline: 2px solid transparent;" class="px-1 rounded border-0">
                        <option value="">All</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <p>Appointments</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <router-link to="/admin/appointments" class="small-box-footer">
                View Appointments
                <i class="fas fa-arrow-circle-right"></i>
            </router-link>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <div class="d-flex justify-content-between">

                	<!-- Cuarto paso del contador de usuarios: la respuesta json del controlador, la pasamos a donde se mostrara el resultado -->
                    <h3>{{ totalUsersCount }}</h3>

                    <!-- Contador de usuarios por dias en el dashboard -->
                    <!-- Primero le damos el metodo para cuando haya un cambio en el select, segundo vamos a crear el metodo -->
                    <!-- Cuarto paso: Le pasamos el v-model para que sepa cual es la referencia que hicimos antes y vamos al quinto paso montando el metodo getUsersCount-->
                    <select v-model="selectedDateRange" @change="getUsersCount()"
                        style="height: 2rem; outline: 2px solid transparent;" class="px-1 rounded border-0">
                        <option value="today">Today</option>
                        <option value="30_days">30 days</option>
                        <option value="60_days">60 days</option>
                        <option value="360_days">360 days</option>
                        <option value="month_to_date">Month to Date</option>
                        <option value="year_to_date">Year to Date</option>
                    </select>
                </div>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <router-link to="/admin/users" class="small-box-footer">
                View Users
                <i class="fas fa-arrow-circle-right"></i>
            </router-link>
        </div>
    </div>
</div>
	</div>
</div> 
</template>

<script setup>

</script>