<script setup>
import axios from 'axios';
import {ref, onMounted, reactive, watch} from 'vue';
import {Form,Field} from 'vee-validate';
import * as yup from 'yup';
import {useToastr} from '../../toastr.js';//notificacion de success para el formulario
import UserListItem from './UserListItem.vue';
import { debounce } from 'lodash';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';


	const toastr = useToastr();

	const users = ref({'data':[]});

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
	const getUsers = (page = 1) =>
	{
		axios.get(`/api/users?page=${page}`)
		.then((response) =>{
			users.value = response.data;

			selectedUsers.value = [];

			selectAll.value = false;
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
			users.value.data.unshift(response.data);

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
	        });
	}

	/*const userDeleted = (userId)=>
	{
		users.value.data = users.value.data.filter(user => user.id !== userId);
	}*/

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

	//referencia para el buscador de usuarios
	const searchQuery = ref(null);

	//Funcion peticion al servidor para la busqueda
	const search = ()=>
	{
		axios.get('/api/users/search',
		{
			params: {
				query: searchQuery.value
			}
		})
		.then(response =>
		{
			users.value = response.data;
		})
		.catch(error => 
		{
			console.log(error);
		})
	}

	watch(searchQuery, debounce(()=>
	{
		search();
	}, 300));

	const selectedUsers = ref([]);

	const toggleSelection = (user) =>
	{
		//id de los usuarios seleccionados
		const index = selectedUsers.value.indexOf(user.id);

		//condicional cuando no encuentra alguno seleccionado
		if (index === -1)
		{
			//Ingresa el value al arreglo de seleccionados
			selectedUsers.value.push(user.id);
		}
		else 
		{
			//Elimina el ultimo que seleccionamos
			selectedUsers.value.splice(index, 1);
		};
	}

	//variable para referencia el usuario que vamos a borrar
	const userIdDelete = ref(null);

	//funcion cuando le damos clic al icono de borrar
	const confirmUserDeletion = (id) =>
	{
		//le sacamos el id y lo guardamos en una variable
		userIdDelete.value = id;
		//modulo de confirmacion mostrandolo
		$('#deleteUserModal').modal('show');
	}

	//funcion para el boton de borrar
	const deleteUser = ()=>
	{
		//le pasamos el id y creamos la ruta en el web.php
		axios.delete(`/api/users/${userIdDelete.value}`)
		.then(()=>
		{
			$('#deleteUserModal').modal('hide');

			toastr.success('User DELETE Successfully');

			users.value.data = users.value.data.filter(user => user.id !== userIdDelete.value);
		});
	}

	const bulkDelete = ()=>
	{
		axios.delete('/api/users',
		{
			data:
			{
				ids:selectedUsers.value
			}
		})
		.then(response =>
		{
			users.value.data = users.value.data.filter(user => !selectedUsers.value.includes(user.id));

			selectedUsers.value = [];

			selectAll.value = false;

			toastr.success(response.data.message);
		})
	}

	//referencia v-model
	const selectAll = ref(false);

	const selectAllUsers = () =>
	{
		if (selectAll.value)
		{
			selectedUsers.value = users.value.data.map(user =>user.id)
		}
		else 
		{
			selectedUsers.value = [];
		};
	}

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
			<div class="d-flex justify-content-between">
				<div class="d-flex">
					<!-- boton para crear un nuevo usuario -->
					<button @click="addUser" type="button" class="mb-2 btn btn-primary">
						<i class="fa fa-plus-circle mr-1"></i>
						Add New User
					</button>

					<!-- boton para borrar el usuario seleccionado -->

					<!-- v-if para aparecer cuando se selecciona algun elemento -->
					<div v-if="selectedUsers.length > 0">
						<button  @click="bulkDelete" type="button" class="mb-2 ml-2 btn btn-danger">
							<i class=" fa fa-trash mr-1"></i>
							Delete Selected 
						</button>

						<span class="ml-2">Selected {{ selectedUsers.length }} users</span>
					</div>
				</div>

				<!-- cuadro de busqueda con vmodel y clickevent -->
				<div >
					<input type="text" v-model="searchQuery" class="form-control " placeholder="Search">
				</div>
			</div>

			<div class="card">	
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<tr>

								<!-- Checkbox para seleccionar todo -->
								<th>
									<input type="checkbox" @change="selectAllUsers" v-model="selectAll"/>
								</th>

								<th style="width: 10px">#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Registered Date</th>
								<th>Role</th>
								<th>Options</th>
							</tr>
						</thead>

						<tbody v-if="users.data.length > 0">
							<!-- Llamamos los eventos del Userlist -->
							<UserListItem v-for="(user,index) in users.data" 

							:key = "user.id"

							:user=user 

							:index=index

							@edit-user = "editUser"

							@confirm-User-Deletion = "confirmUserDeletion"

							@toggle-selection="toggleSelection" 
							:select-all='selectAll'
							/>		
						</tbody>

						<tbody v-else>
							<tr>
								<td colspan="6" class="text-center">No results found...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

		<Bootstrap4Pagination
	        :data="users"
	        @pagination-change-page="getUsers"
	    />
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

		<div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
	        aria-labelledby="staticBackdropLabel" aria-hidden="true">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h5 class="modal-title" id="staticBackdropLabel">
	                    	<span>DELETE USER</span>
	                    </h5>
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>

	                <div class="modal-body">
	                	<h5>Are you sure deleting this user?</h5>
	                </div>

	                <div class="modal-footer">
	                	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

		                <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete</button>
	                </div>
	        </div>
	    </div>
	</div>
</template>

