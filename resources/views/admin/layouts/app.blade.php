
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AdminLTE 3 | Starter</title>

	@vite(['resources/css/app.css', 'resources/js/app.js'])
<!--  
	<link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">-->
</head>

<!-- Usar localStorage para que el sidebar se quede como la ultima vez collapsada o no -->

<body class="hold-transition sidebar-mini">
		<!--  EStamos pasando el cuerpo desde un componente vue: App.vue -->
		<div id="app">

	    </div>

	<!--  segundo paso sidebar: Creamos el evento para cuando se hace clic en el boton de despliegue-->
	<script>
		document.addEventListener('DOMContentLoaded', () =>
		{
			//seleccionamos el id que dimos anteriormenteen el boton para desplegar el menu
			const toggleMenuIcon = document.querySelector('#toggleMenuIcon');	

			const body = document.querySelector('body');	

			//Vamos a crear el evento al darle clic al boton de despliegue del menu
			toggleMenuIcon.addEventListener('click', ()=>
			{
				if (body.classList.contains('sidebar-collapse'))
				{
					//Le damos la propiedad expande al key sidebarState
					localStorage.setItem('sidebarState', 'expanded');
				}
				else
				{
					//Le damos la propiedad collapse al key sidebarState
					localStorage.setItem('sidebarState', 'collapsed');
				}
			});

			//Pasamos la key, la cual esta dentro de sidebarState
			const sidebarState = localStorage.getItem('sidebarState');

			if (sidebarState === 'collapsed')
			{
				body.classList.add('sidebar-collapse');
			}
		});

	</script>

</body>
</html>


<div class="float-right d-none d-sm-inline">
	Anything you want
</div>