<script setup>

//importamos referencia 
import {ref} from 'vue';
import {useToastr} from '../../toastr.js';//notificacion de success para el formulario


	const toastr = useToastr();

	const users = ref([]);

	const roles = ref([
		{
			name: 'ADMIN',
			value:1
		},
		{
			name: 'USER',
			value:2
		}
	]);

	//Aqui se definen los eventos emits
	const emit = defineEmits(['userDeleted', 'editUser','confirmUserDeletion']);

	//se le pasan los dos props al componente
	const props = defineProps(
	{
		user: Object,

		index: Number,

		selectAll: Boolean,
	});

	//variable para referencia el usuario que vamos a borrar
	const userIdDelete = ref(null);

	//funcion cuando le damos clic al icono de borrar
	const confirmUserDeletion = (user) =>
	{
		//le sacamos el id y lo guardamos en una variable
		userIdDelete.value = user.id;
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

			emit('userDeleted', userIdDelete.value);
		
		});
	}

	const changeRole = (user, role) =>
	{
		axios.patch(`/api/users/${user.id}/change-role`,
		{
			role: role,
		})
		.then(()=>{
			toastr.success('Role Changed Successfully');
		})
	}

	const toggleSelection = ()=>
	{
		emit('toggleSelection', props.user);
	}

	/*Podemos crear esta funcion desde el template directamente
	const editUser = (user)=>
	{
		emit('editUser', user)
	}*/

</script>

<template>
	<tr>
		<td>
			<input type="checkbox" :checked="selectAll" @change="toggleSelection"/>
		</td>
		<td>{{index + 1}}</td>
		<td>{{user.name}}</td>
		<td>{{user.email}}</td>

		<!-- funcion de la fecha en UserController -->
		<td>{{user.created_at}}</td>
		<td>
			<select class="form-control" @change="changeRole(user, $event.target.value)">
				<option v-for="role in roles" :value="role.value" :selected="(user.role === user.name)" >{{role.name}}</option>
			</select>
		</td>
		<td>
			<a href="#">
				<i class="fa fa-edit" @click.prevent="$emit('editUser', user)"></i>
			</a>
			<a href="#">
				<i class="fa fa-trash text-danger ml-2" @click.prevent="confirmUserDeletion(user)"></i>
			</a>
		</td>
	</tr>


	<!-- Confirmacion para eliminar user -->
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

