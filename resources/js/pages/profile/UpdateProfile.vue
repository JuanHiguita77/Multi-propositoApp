<script setup>

	import { ref, onMounted } from 'vue';
	import { useToastr } from '@/toastr';

	//variable para mostrar el mensaje satisfactorio
	const toastr = useToastr();

	//variable para los errores de validacion 
	const errors = ref([]);

	//Cuarto paso para el profile: definimos los campos de donde se van a extraer los datos y vamos a ProfileController.php
	const form = ref({
		name: '',
		email: '',
		role: '',
	});


	//tercer paso para el profile: Obtenemos el usuario completo
	const getUser = () =>
	{
		axios.get('/api/profile')

		.then((response) =>
		{
			form.value = response.data;
		})
	}

	//Septimo paso: Mandamos la informacion con un put desde el formulario, ahora vamos a crear su endpoint o ruta en web.php
	const updateProfile = () =>
	{
		//Noveno paso: Despues de tener la respuesta json vamos a mostrar el mensaje de actualizacion satisfactorio
		axios.put('/api/profile', form.value)

		.then((response) =>
		{
			toastr.success('User Updated');
		})

		//Ahora tenemos que mostrar los mensajes de error
		.catch((error) =>
		{
			if (error.response && error.response.status === 422)
			{
				errors.value = error.response.data.errors;
			};
		})
	}


	//Sexto paso del profile: Montamos el metodo para obtener el usuario y mostramos el dato al usuario si es necesario como en este caso
	//Se muestra la informacion en el html en este caso: {{ form.name }}
	onMounted(()=>
	{
		getUser();
	});


</script>

<template>
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Profile</h1>
				</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Profile</li>
				</ol>
			</div>
			</div>
		</div>	
	</div>		


	<div class="content">
		<div class="container-fluid">
			<div class="row">
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <input type="file" class="d-none">
                    <img class="profile-user-img img-circle" src="" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ form.name }}</h3>

                <p class="text-muted text-center">{{ form.role }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab"><i class="fa fa-user mr-1"></i> Edit Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="#changePassword" data-toggle="tab"><i class="fa fa-key mr-1"></i> Change
                            Password</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                    	<!-- Le pasamos el metodo para actualizar los datos al darle submit al boton -->
                        <form @submit.prevent="updateProfile()" class="form-horizontal">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input v-model="form.name" type="text" class="form-control" id="inputName" placeholder="Name">

									<!--   Mostramos el mensaje de error en caso tal -->
                                    <span class="text-danger text-sm" v-if="errors && errors.name">{{ errors.name[0] }}</span>
                                </div>

                                
                            </div>
                            <div class="form-group row">
                                <label   for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input v-model="form.email" type="email" class="form-control " id="inputEmail" placeholder="Email">
                                    
                                    <!--   Mostramos el mensaje de error en caso tal -->
                                    <span class="text-danger text-sm" v-if="errors && errors.email">{{ errors.email[0] }}</span>
                                </div>

                                
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="changePassword">
                        <form class="form-horizontal">
                            <div class="form-group row">
                                <label for="currentPassword" class="col-sm-3 col-form-label">Current
                                    Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control " id="currentPassword" placeholder="Current Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="newPassword" class="col-sm-3 col-form-label">New
                                    Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control " id="newPassword" placeholder="New Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="passwordConfirmation" class="col-sm-3 col-form-label">Confirm
                                    New Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control " id="passwordConfirmation" placeholder="Confirm New Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-9">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
		</div>
	</div>

</template>

