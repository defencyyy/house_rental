<nav id="sidebar" class = "sidebar-style" >
		
		<div class="sidebar-list">
				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-tachometer-alt "></i></span> Dashboard</a>
				<a href="index.php?page=categories" class="nav-item nav-categories"><span class='icon-field'><i class="fa fa-home"></i></span> Manage Categories</a>
				<a href="index.php?page=houses" class="nav-item nav-houses"><span class='icon-field'><i class="fa fa-user-friends  "></i></span> Manage Apartment</a>
				<a href="index.php?page=tenants" class="nav-item nav-tenants"><span class='icon-field'><i class="fa fa-list-alt "></i></span> Manage Tenants</a>
				<a href="index.php?page=invoices" class="nav-item nav-invoices"><span class='icon-field'><i class="fa fa-file-invoice "></i></span> View Payments</a>
		</div>

</nav>
<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
