<?php require_once '../extra/check_admin.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito Sans">
	<link rel="stylesheet" type="text/css" href="admin.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Đây là giao diện admin!</title>
</head>
<style type="text/css">
	body {
		height: 1000px;
	}
	table{
		width: 100%;
		padding: 30px 10px 0px 15px;
		position: relative;
		background-color: rgba(0, 0, 0,0.05);
		font-size: 20px;
		padding: 0;
		padding-left: 1em;
	}
	input{
		border-radius: 30px;
		font-family: Nunito Sans;monospace;
		font-size: 19px;
	}
	button{
		border: 0;
		background-color: skyblue;
	}
	
</style>
<body>
	<?php include 'theme.php' ?>
	<div class="div-phai"  >
		<head>
			<div class="div-tren">
				<a href=""class="trai-div-tren">
				Các sản phẩm</a>
				<a href="../in_and_out/signout.php" class="logout">Đăng xuất</a>
			</div>
		<main style="padding:1em 0 0 1em">


			<?php
			include '../extra/connect.php';
			require_once '../extra/pagi1.php';


			$result=mysqli_query($connect,"select count(*) as total_product from product where product_name like '%$search%'");
			$result=$result->fetch_array()["total_product"];
			require_once '../extra/pagi2.php';

			$sql="select * from product where product_name like '%$search%' limit $items offset $skip";
					$all_product=mysqli_query($connect,$sql);
			?>


			<p style="font-size:19px;padding-left:1em;padding-bottom:0.25em;">
			Số lượng mặt hàng hiện tại:<?php echo $result; ?>
			</p>

			<form style="font-size:19px;padding-left:1em;padding-bottom:1em;">Tìm kiếm mặt hàng: 
				<input type="text" name="search" placeholder="Nhập tên mặt hàng">
			</form>
			<table>
			
				<tr>
					<td>ID</td>
					<td>Tên sản phẩm</td>
					<td>Ảnh sản phẩm</td>
					<td>Nhà sản xuất</td>
					<td>Giá</td>
					<td>Mô tả</td>
					<td>Kích thước</td>
					<td>Loại</td>
				</tr>
				<?php 
					
					foreach ($all_product as $each) {
						//get manufacturer_name
						$id_manu=$each['manufacturer_id'];
						$name_manu=mysqli_query($connect,"select manufacturer_name
						 from manufacturer where manufacturer_id='$id_manu'");
						$name_manu=$name_manu->fetch_array()['manufacturer_name'];
						$each['manufacturer_id']=$name_manu;
						//get type_name
						$id_type=$each['type_id'];
						$type=mysqli_query($connect,"select type_name
						 from type where type_id='$id_type'");
						$type=$type->fetch_array()['type_name'];
						$each['type_id']=$type;
					 ?>
				<tr>
					
					 <td><?php echo $each['product_id'] ?></td>
					 <td><?php echo $each['product_name'] ?></td>
					 <td><img src="pic_product/<?php echo $each['product_image'] ?>" style="height: 100px;width:100px;"></td>
					 <td><?php echo $each['manufacturer_id'] ?></td>
					 <td><?php echo $each['price'] ?></td>
					 <td><?php echo $each['description'] ?></td>
					 <td><?php echo $each['product_size'] ?></td>
					 <td><?php echo $each['type_id'] ?></td>
					<td><a href="./product_process/update_product.php?id=<?php echo $each['product_id']  ?>"><button>Sửa</button></a> </td>
					 <td><a href="./product_process/delete_product.php?id=<?php echo $each['product_id'] ?>"><button>Xóa</button></a> </td>					
				</tr>
				<?php } ?>
			</table>
		<?php require_once '../extra/pagi3.php';  ?>	
		</main>
		
		</head>
	</div>

</body>
</html>