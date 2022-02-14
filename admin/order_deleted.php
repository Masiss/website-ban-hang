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
		font-family: Nunito Sans, monospace;
		width: 100%;
		position: relative;
		background-color: rgba(0, 0, 0, 0.05);
		font-size: 20px;
	}
	main{
		padding: 1em;
	}
	.page{
		color: darkturquoise;
		border: 1px solid;
		margin: 3px;
		padding: 1px;
	}
	button{
		border: none;
		background-color: skyblue;
		color: white;
		font-weight: 700;
	}
</style>
<body>
	<?php include 'theme.php' ?>
	<div class="div-phai"  >
		<head>
			<div class="div-tren">
				
				<a href="" class="trai-div-tren" >Các đơn hàng đã hủy:</a>
				<a href="../in_and_out/signout.php" class="logout">Đăng xuất</a>
			</div>	
		</head>
		<main>
			<?php 
			include '../extra/connect.php';
			include '../extra/pagi1.php';
			$result=mysqli_query($connect,"select count(*) as total_bill from bill_detail where status='đã hủy'")->fetch_array()['total_bill'];
			include '../extra/pagi2.php';


			?>
			<p style="font-size: 19px;">Số lượng đơn hàng đã xóa: <?php echo $result; ?> </p>
			<table>
				<tr>
					<td>Mã đơn</td>
					<td>Tên mặt hàng</td>
					<td>Người mua</td>
					<td>Địa chỉ</td>
					<td>Số điện thoại</td>
					<td>Thời gian tạo đơn</td>
					<td>Ghi chú</td>
				</tr>
				<?php 
				$bill_detail=mysqli_query($connect,"select bill.*,bill_detail.*  from bill  JOIN bill_detail on bill_detail.bill_id=bill.bill_id where status='đã hủy' limit $items offset $skip");
				foreach ($bill_detail as $each) {
					?>
					<tr>
						<td><?php echo $each['bill_id'] ?></td>
						<td>
							<?php 
							$product_details=json_decode($each['product_details'],true);
							foreach ($product_details as $key => $value) {
								$product_name=mysqli_query($connect,"select product_name from product where product_id='$key'")->fetch_array()['product_name'];
								echo $product_name;
								?>
								<?php echo 'size: '.$value['size'] ?>
								<br>							
							<?php } ?>

						</td>
						<td><?php echo $each['user_name'] ?></td>
						<td><?php echo $each['user_address'] ?></td>
						<td><?php echo '0'.$each['user_phone'] ?></td>
						<td><?php echo $each['create_at'] ?></td>
						<td><?php echo $each['note'] ?></td>
						<td>
							<a target="_blank" href="./view_bill.php?id=<?php echo $each['bill_id'] ?>">
								<button>
									Chi tiết
								</button>
							</a>
						</td>




					</tr>
				<?php } ?>
			</table>
			<?php include '../extra/pagi3.php' ?>
		</main>
	</div>

</body>
</html>