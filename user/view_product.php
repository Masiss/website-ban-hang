<?php include './process/check_login.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans" rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css2?family=Rowdies" rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="user.css">
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
	<title>Masiss Pomade</title>
</head>
<style type="text/css">
	body{
		height: 1000px;

	}
	main{
		margin: 100px;
		margin-top: 0;
		height: 100%;
	}
	img{
		max-width: 100%;
		max-height: 100%;

	}
	.product_info{
		display: inline-block;
		width:50%;
		height:fit-content;
	}
	.product_info p{
		font-weight: 300;
		color: black;
		font-size: 19px;
		height: 300px;
	}
	.product_info div {

		margin: 30px;
		width: 100%;

	}
	.product_info div p{
		text-align: left;
		text-transform: none;
		width: auto;
		height: fit-content;

	}
	span{
		color: black;
		font-size: 30px;
		font-weight: 700;
		margin-left: 30px;

	}
	button{
		box-sizing: border-box;
		border-radius: 25px;
		padding: 10px;
		font-size: 15px;
		background-color: skyblue;
	}
	#announce{
		font-size: 19px;
		position: absolute;
		top: 250px;
		color: green;
	}
	select{
		font-size:17px ;
		padding: 10px;
	}
	option{
		
	}
</style>
<body>
	<?php include 'theme1.php'; ?>
	<main style="display:flex;">
		<?php 
		include '../extra/connect.php';
		if(empty($_GET['id'])){
			header('Location:./test.php');
			exit();
		}
		$id=$_GET['id'];
		$get_info=mysqli_query($connect,"select product.*, manufacturer.manufacturer_name as 'manufacturer_name' from product join manufacturer on product.manufacturer_id=manufacturer.manufacturer_id where product_id='$id'")->fetch_array();
		$get_same_product=mysqli_query($connect,"select * from product where product_name='{$get_info['product_name']}' and description='{$get_info['description']}' and product_id!={$get_info['product_id']}");
		$arr_size=[];
		$arr_price=[];
		$min=$get_info['price'];
		if(!empty($get_same_product)){
			foreach ($get_same_product as $key) {
				$min=min($min,$key['price']);
				$max=max($get_info['price'],$key['price']);
				array_push($arr_size,[$key['product_size']=>$key['product_id']]);
				array_push($arr_price,$key['price']);
			}
		}
		
		?>
		<div style="display:flex; width:60%;height: 100%;margin-left: 150px;flex-direction: column;justify-content:flex-start;">
			<input id="id_product" type="hidden" name="" value="<?php echo $get_info['product_id'] ?>">
			<span>
				<?php echo $get_info['product_name']; ?>
			</span>
			<figure>
				<img src="../admin/pic_product/<?php echo $get_info['product_image'] ?>">

			</figure>
		</div>
		<div  class="product_info">
			<div>

				<span id="announce"></span>
				<p>Giá:
					<?php
					if(isset($max)){
						echo $min.'->'.$max;
					} else echo $get_info['price'];
				?>đ
			</p>
			<br>
			<p> Nhà sản xuất:
				<?php echo $get_info['manufacturer_name'] ?>
			</p>
			<br>
			<p>

				<form>
					<select id="size">
						<option></option>
						<option data-price="<?php echo $get_info['price'] ?>"
							data-id="<?php echo $get_info['product_id'] ?>">
							<?php echo $get_info['product_size'] ?>
						</option>
						<?php if(isset($arr_size)){
							for($i=0;$i<count($arr_size);$i++)
								{ $key=key($arr_size[$i]);
									?>
									<option data-price="<?php echo $arr_price[$i] ?>" 
										data-id="<?php echo $arr_size[$i][$key] ?>">
										<?php echo $key ?></option>
									<?php }
								}?>
							</select>

							<button class="btn-add">Thêm vào giỏ hàng</button>
						</form>
						<b style="width:100%; display:block; margin:30px 10px;border-color: black;"></b>
						<span style="visibility: hidden;" id="pfs"></span>
					</p>
				</div>
				<p style="height:100%;display:flex;justify-content: space-between;">
					<?php 
					$type=explode(',',$get_info['type_id']);
					foreach($type as $each){
						$each=mysqli_query($connect,"select type_name from type where type_id='$each'")->fetch_array()['type_name'];
						?>
						<a style="background: skyblue;color: white;font-weight: 700;font-size: 19px;border: 1px solid;box-sizing: border-box;margin:30px 30px;padding:10px 30px;;" href="./search.php?search=&&type=<?php echo $each ?>"><?php echo $each ?></a>
					<?php } ?>
				</p>
				<p style="text-align: left;padding: 60px 30px;text-transform:none">
					Thông tin sản phẩm:
					<br>
					<br>
					<?php echo $get_info['description'] ?>
				</p>
			</div>
		</main>
		<?php include './theme2.php'; ?>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"> </script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".btn-add").click(function(event) {
				event.preventDefault();
				let btn=$(this);
				let size=$("select").val();
				let id=$("option:selected").data('id');
				if(size==""){
					document.getElementById('announce').innerHTML="Vui lòng chọn kích thước";
					return;
				}

				$.ajax({
					url: './process/add_cart.php',
					data: {id,size},
				})
				.done(function() {
					$("#announce").text("Thêm vào giỏ hàng thành công");
				})


			});
		});
		$("select").change(function(event) {
			let size=document.getElementById('size');
			if(size.options[size.selectedIndex].text==""){
				document.getElementById('pfs').innerHTML="";
				document.getElementById('pfs').style.visibility="hidden";
				return;
			}
			
			
			let price=$("option:selected").data('price');
			document.getElementById('pfs').innerHTML=price+"đ";
			document.getElementById('pfs').style.visibility="visible";
		});
	</script>
	</html>