<script setup>
import axios from 'axios';
import {ref, onMounted, reactive} from 'vue';
import {Form,Field} from 'vee-validate';
import * as yup from 'yup';
import {useToastr} from '../../toastr.js';//notificacion de success para el formulario


	const toastr = useToastr();

	const users = ref([]);

	const editing = ref(false);

	const formValues = ref();

	//Se define como null para evitar errores
	const form = ref(null);

	//OBJETO CON LOS NUEVOS DATOS
	/*const form = reactive({
		name: '',
		email:'',
		password:'',
	});*/



	//metodo get para obtener usuarios de base de datos
	const getUsers = () =>
	{
		axios.get('/api/users')
		.then((response) =>{
			users.value = response.data;
		});
	}

	onMounted(()=>
	{
		getUsers();
	});


	//Nuevo metodo con veevalidate para el formulario usando sus componentes, mensaje de error 
	const createUser = (values, {resetForm, setErrors}) => {
		axios.post('/api/users', values)
		.then((response) => 
		{
			users.value.unshift(response.data);

			$('#userFormModal').modal('hide');
			resetForm();

			toastr.success('User Created Successfully');
		})
		.catch((error) => 
		{
			if (error.response.data.errors)
			{
				setErrors(error.response.data.errors);
			};
		})
	};

	//validacion de datos y minimo de caracteres para pass
	const createUserschema = yup.object({
		name: yup.string().required(),
		email: yup.string().required().email(),
		password: yup.string().required().min(8),
	});


	//segunda validacion de datos al editar un usuario sin contraseña
	const editUserschema = yup.object({
		name: yup.string().required(),
		email: yup.string().required().email(),
		password: yup.string().when((password, schema) => {
			return password ?  schema : schema.required().min(8);
		}),

	});

	//cambiamos el valor a true o false segun el boton al que le demos

	//mostrar el mismo formulario para añadir un usuario
	const addUser = () =>
	{
		editing.value = false;

		$('#userFormModal').modal('show');
	};

	//mostrar el mismo formulario para editar un usuario
	const editUser = (user) =>
	{
		editing.value = true;

		//indicamos con la referencia 'form' que se reinicie
		form.value.resetForm();

		$('#userFormModal').modal('show');

		//Le decimos con que se van a llenar los datos de nuevo
		formValues.value = 
		{
			id: user.id,
			name: user.name,
			email: user.email,
		}
	};

	//Metodo para actualizar el usuario
	const updateUser = (values, { setErrors }) => 
	{
	    axios.put('/api/users/' + formValues.value.id, values)
	        .then((response) => {
	            const index = users.value.findIndex(user => user.id === response.data.id);

	            users.value[index] = response.data;

	            $('#userFormModal').modal('hide');	          
				toastr.success('User Update Successfully');

	        })
	        .catch((error) => {
	            setErrors(error.response.data.errors);
	            console.log(error);
	        });
	}

	//Metodo para enviar los datos a un metodo u otro
	//llamamos al componente actions que contiene eventos tales como resetForm el cual usamos mas adelante
	const handleSubmit = (values, actions) =>
	{
		if (editing.value)
		{
			updateUser(values, actions);
		}
		else 
		{
			createUser(values, actions);
		};
	}


	//metodo post para enviar usuarios nuevos con formulario
/*	const createUser = () =>
	{
		axios.post('/api/users', form)
		.then((response) => 
		{
			users.value.push(response.data);
			form.name = '',
			form.email = '',
			form.password = '',

			$('userFormModal').modal('hide');
		});
	}*/

</script>

<template>
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Users</h1>
				</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Users</li>
				</ol>
			</div>
			</div>
		</div>	
	</div>		

<!-- Tabla de usuarios -->
	<div class="content">
		<div class="container-fluid">
			<button @click="addUser" type="button" class="mb-2 btn btn-primary">
				Add New User
			</button>

			<div class="card">	
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Registered Date</th>
								<th>Role</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(user,index) in users" :key="user.id">
								<td>{{index}}</td>
								<td>{{user.name}}</td>
								<td>{{user.email}}</td>
								<td>-</td>
								<td>-</td>
								<td>
									<a href="#">
										<i class="fa fa-edit" @click.prevent="editUser(user)"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

<!-- formulario -->
	<div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
	        aria-labelledby="staticBackdropLabel" aria-hidden="true">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h5 class="modal-title" id="staticBackdropLabel">
	                    	<span v-if="editing">EDIT USER</span>
	                    	<span v-else>ADD NEW USER</span>
	                    </h5>
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>

	                <!-- Le pasamos la accion con submit, el esquema de validacion y el mensaje de error -->
	                <Form ref="form" @submit="handleSubmit" :validation-schema=" editing ? editUserschema : createUserschema" v-slot="{errors}" :initial-values="formValues">
	                    <div class="modal-body">
	                        <div class="form-group" >
	                            <label for="name">Name</label>

	                            <Field name="name" type="text" class="form-control " id="name"
	                                aria-describedby="nameHelp" placeholder="Enter full name" :class="{'is-invalid': errors.name}"/>

	                            <span class="invalid-feedback">{{errors.name}}</span>
	                        </div>

	                        <div class="form-group">
	                            <label for="email">Email</label>
	                            <Field name="email" type="email" class="form-control " id="email"
	                                aria-describedby="nameHelp" placeholder="Enter full name" :class="{'is-invalid': errors.email}"/>

	                            <span class="invalid-feedback">{{errors.email}}</span>
	                        </div>

	                    

		                    <div class="form-group">
		                        <label for="email">Password</label>
		                        <Field name="password" type="password" class="form-control " id="password"
		                            aria-describedby="nameHelp" placeholder="Enter password" :class="{'is-invalid': errors.password}"/>

		                        <span class="invalid-feedback">{{errors.password}}</span>
		                    </div>
	                	</div>

		                <!-- Accion del boton para agregar usuario -->
		                <div class="modal-footer">
		                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		                    <!-- metodo createUser -->
		                    <button type="submit" class="btn btn-primary">Save</button>
		                </div>
		            </Form>
	            </div>
	        </div>
	    </div>
</template>

