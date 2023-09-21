<script setup>

	import { ref, onMounted, reactive } from 'vue';
	import { useToastr } from '@/toastr';

	//variable para mostrar el mensaje satisfactorio
	const toastr = useToastr();

	//variable para los errores de validacion 
	const errors = ref([]);

	const fileInput = ref();

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

	//primer paso para la confirmacion de contraseña: Hacer el objeto reactivo para detectar sus cambios automaticamente
	const changePasswordForm = reactive({
		currentPassword:'',
		password: '',
		passwordConfirmation: '',

	});

	//Segundo paso para la confirmacion de contraseña: Crear el evento al subir el formulario, se le asigna en la etique form
	const handleChangePassword = ()=>
	{
		//campo de errores vacio para iniciar
		errors.value = '';

		//le pasamos la informacion del formulario y creamos el endpoint
		axios.post('/api/change-user-password', changePasswordForm)

		.then((response) =>
		{
			//respuesta json que llega desde el controlador
			toastr.success(response.data.message); 

			//Sexto paso para actualizar la contraseña: Despues de obtener la respuesta ponemos en blanco los campos
			for(const field in changePasswordForm)
			{
				changePasswordForm[field] = '';
			}
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

	//cuarto paso paso para la imagen del perfil: creamos el metodo para abrir la ventana de seleccion de la foto
	const openFileInput = () =>
	{
		//llamamos la referencia puesta en el input
		fileInput.value.click();
	}

	//Referencia  la direccion del archivo almacenado en url
	const profilePictureUrl = ref(null);

	//Quinto paso para cambiar foto de perfil: Creamos metodo para pasar la imagen a una url y luego lo pasamos en el src del template
	const handleFileChange = (event) =>
	{
		//Tomamos el primer archivo
		const file = event.target.files[0];

		//tranforma la imagen en url y se la asigna al valor de la variable
		profilePictureUrl.value = URL.createObjectURL(file);

		//Sexto paso para la foto de perfil: Creamos el objeto para meterle los archivos
		const formData = new FormData();

		formData.append('profile_picture', file);

		//Creamos la ruta para la imagen subida en web.php
		axios.post('/api/upload-profile-image', formData)  

		.then((response) =>
		{
			toastr.success('Image uploaded successfully');
		});
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
                	<!-- Segundo paso para la imagen del perfil: Refenciamos el input  y luego creamos la variable referenciadora fileInput = ref()-->
                	<!-- Quinto paso paso para la imagen del perfil: le pasamos el change para poder actualizarla en caso tal -->
                    <input @change="handleFileChange" ref="fileInput" type="file" class="d-none">
                    <!-- Tercer paso para la imagen del perfil: Llamamos el metodo para abrir la ventana de selección para el archivo -->
                    														<!-- Le pasamos un condicional para pasar la url o una imagen personalizada para cuando no hay nada -->
                    <img @click="openFileInput" class="profile-user-img img-circle" :src="profilePictureUrl ? profilePictureUrl : form.avatar " alt="User profile picture">
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
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Save Changes </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="changePassword">
                    	<!-- Segundo paso para la confirmacion de contraseña: darle el evento y crear la funcion -->
                        <form @submit.prevent="handleChangePassword" class="form-horizontal">
                            <div class="form-group row">
                            	
                                <label  for="currentPassword" class="col-sm-3 col-form-label">Current
                                    Password</label>
                                <div class="col-sm-9">
                                	<!-- Quinto paso para actualizar la contraseña: le pasamos los vmodel -->
                                    <input v-model="changePasswordForm.currentPassword" type="password" class="form-control " id="currentPassword" placeholder="Current Password">

                                    <!--   Mostramos el mensaje de error en caso tal -->
                                    <span class="text-danger text-sm" v-if="errors && errors.current_password">{{ errors.current_password[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="newPassword" class="col-sm-3 col-form-label">New
                                    Password</label>
                                <div class="col-sm-9">
                                    <input v-model="changePasswordForm.password" type="password" class="form-control " id="newPassword" placeholder="New Password">

                                    <!--   Mostramos el mensaje de error en caso tal -->
                                    <span class="text-danger text-sm" v-if="errors && errors.password">{{ errors.password[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="passwordConfirmation" class="col-sm-3 col-form-label">Confirm
                                    New Password</label>
                                <div class="col-sm-9">
                                    <input v-model="changePasswordForm.passwordConfirmation" type="password" class="form-control " id="passwordConfirmation" placeholder="Confirm New Password">

                                    <!--   Mostramos el mensaje de error en caso tal -->
                                    <span class="text-danger text-sm" v-if="errors && errors.current_password">{{ errors.current_password[0] }}</span>
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

//Primer paso para cambiar la foto del usuario
<style>
	/*Clase de la etiqueta contenedora de la imagen de perfil*/
	.profile-user-img:hover
	{
		background-color: #0216AE;

		cursor:pointer;
	}
</style>

