<?php include('db_connect.php');?>

<h2 class="titlepage">Houses</h2>

<div class="container-fluid tablepos">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-house">
					<div class="card">
						<div class="card-header">
							House Form
						</div>
						<div class="card-body">
							<div class="form-group" id="msg"></div>
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">House Number</label>
								<input type="text" class="form-control" name="house_no" required>
							</div>
							<div class="form-group">
								<label class="control-label">Renting Price</label>
								<input type="number" class="form-control text-right" name="price" step="any" required>
							</div>
							<div class="form-group">
								<label class="control-label">House Category</label>
								<select name="category_id" id="" class="custom-select" required>
									<?php 
									$user_id = $_SESSION['login_id'];
									$categories = $conn->query("SELECT * FROM categories WHERE user_id = '$user_id' ORDER BY id ASC");
									if($categories->num_rows > 0):
									while($row= $categories->fetch_assoc()) :
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
									<?php else: ?>
									<option selected="" value="" disabled="">Please check the category list.</option>
									<?php endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">House Capacity</label>
								<select name="capacity" id="" class="custom-select" required>
									<option value="1">1-3</option>
									<option value="2">4-6</option>
									<option value="3">7-9</option>
									<option value="4">10+</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Occupancy Status</label>
								<select name="occupancy_status" id="" class="custom-select" required>
									<option value="Vacant">Vacant</option>
									<option value="Occupied">Occupied</option>
									<option value="Maintenance">Under Maintenance</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Address</label>
								<input type="text" class="form-control" name="address">
							</div>
							<div class="form-group">
								<label for="" class="control-label">Description</label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control"></textarea>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="col-sm-3 offset-md-3 savebtn"> Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="reset"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>House List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">House</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$user_id = $_SESSION['login_id'];
								$i = 1;
								$house = $conn->query("SELECT h.*, c.name as cname FROM houses h 
                                      INNER JOIN categories c ON c.id = h.category_id 
                                      WHERE h.user_id = '$user_id' 
                                      ORDER BY id ASC");
								while($row = $house->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p>House #: <b><?php echo $row['house_no'] ?></b></p>
										<p><small>House Type: <b><?php echo $row['cname'] ?></b></small></p>
										<p><small>Occupancy Status: <b><?php echo ucfirst($row['occupancy_status']) ?></b></small></p>
										<p><small>Price: <b><?php echo number_format($row['price'], 2) ?></b></small></p>
										<p><small>Capacity: <b><?php echo $row['capacity'] ?></b></small></p>
										<p><small>Address: <b><?php echo $row['address'] ?></b></small></p>
										<p><small>Description: <b><?php echo $row['description'] ?></b></small></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_house" type="button" data-id="<?php echo $row['id'] ?>"  data-house_no="<?php echo $row['house_no'] ?>" data-description="<?php echo $row['description'] ?>" data-category_id="<?php echo $row['category_id'] ?>" data-price="<?php
										echo $row['price'] ?>" data-capacity="<?php echo $row['capacity'] ?>" data-occupancy_status="<?php echo $row['occupancy_status'] ?>" data-address="<?php echo $row['address'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_house" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	
</div>

<style>
	td{
		vertical-align: middle !important;
	}
	td p {
		margin: unset;
		padding: unset;
		line-height: 1em;
	}
</style>

<script>
	$('#manage-house').on('reset', function(e){
		$('#msg').html('');
	});

	$('#manage-house').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');
		var form = $(this)[0];
		var formData = new FormData(form);
		formData.append('user_id', <?php echo $_SESSION['login_id']; ?>);
		$.ajax({
			url:'ajax.php?action=save_house',
			data: formData,
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success');
					setTimeout(function(){
						location.reload();
					},1500);
				} else if(resp==2){
					$('#msg').html('<div class="alert alert-danger">House number already exists.</div>');
					end_load();
				}
			}
		});
	});

	$('.edit_house').click(function(){
		start_load();
		var cat = $('#manage-house');
		cat.get(0).reset();
		cat.find("[name='id']").val($(this).attr('data-id'));
		cat.find("[name='house_no']").val($(this).attr('data-house_no'));
		cat.find("[name='description']").val($(this).attr('data-description'));
		cat.find("[name='price']").val($(this).attr('data-price'));
		cat.find("[name='category_id']").val($(this).attr('data-category_id'));
		cat.find("[name='occupancy_status']").val($(this).attr('data-occupancy_status'));
		cat.find("[name='address']").val($(this).attr('data-address'));
		var capacityValue = $(this).attr('data-capacity');
		cat.find("[name='capacity'] option[value='" + capacityValue + "']").prop('selected', true);
		end_load();
	});

	$('.delete_house').click(function(){
		_conf("Are you sure to delete this house?","delete_house",[$(this).attr('data-id')]);
	});

	function delete_house($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_house',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success');
					setTimeout(function(){
						location.reload();
					},1500);
				}
			}
		});
	}

	$('table').dataTable();
</script>
