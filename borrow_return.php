<!--这个文件实现了借阅卡的功能，准确的说，就是向用户确定要借器材的数量-->
<!--文件主要分为3个部分：获取index.php传来的参数；借阅页面布局 ;mysql表格新增一条借阅记录-->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href=".\borrow_return.css">
	<title>借阅卡</title>
</head>
<body style="text-align: center;">
<!--第1部分：获取index.php传来的参数-->
	<?php  
		$account_id_string=explode('&', $_SERVER['QUERY_STRING'])[0];
			$equip_id_string=explode('&', $_SERVER['QUERY_STRING'])[1];
			$residue_string=explode('&', $_SERVER['QUERY_STRING'])[2];

			$account_id=intval(explode('=', $account_id_string)[1]) ;//取出参数值
			$equip_id=intval(explode('=', $equip_id_string)[1]);
			$residue=intval(explode('=', $residue_string)[1]);//取出仓库剩余，用于判断用户想取的量是否超过。

			$image_location="images/equipment/".$equip_id.".avif";//构造图片位置
			?>
<!--第2部分：借阅布局-->
	<img src="<?php echo $image_location;?>" style="width: 220px;height: 220px;direction: block;"/>

			<form action="" method="post" name="form">

				<div>账号：<?php echo $account_id;?></div>
				<br/>

				<div>器材号：<?php echo $equip_id;?></div>
				<br/>

				<div>仓库剩余：<?php echo $residue;?></div>
				<br/>

				<input type="number" placeholder="lend_number" name="lend_number" max="<?php echo $residue;?>" min="1" required>
				<br/>

				<input type="text" placeholder="in_storage_time" name="in_storage_time" required>
				<br/>

				<input value="提交" type="submit" ><br>
		</form>
<!--第3部分：获取当前页面的url，从而获取来自index.php的参数传递，完成借阅卡任务-->
		<?php
			$lend_number=$_POST['lend_number'];
			$in_storage_time=$_POST['in_storage_time'];
			require_once('.\mysql_access.php');
			$result=borrow($account_id,$equip_id,$lend_number,$in_storage_time);

			//给用户提示器材的位置，钥匙的位置，需要检查的东西
			if($result){
				require_once('.\mysql_access.php');
				$searchRecords=searchAllData('');
				printf("器材所在屋子：%d\n",$searchRecords[$equip_id-1][4]);
				printf("器材所在柜号(柜子编号)：%d\n",$searchRecords[$equip_id-1][5]);
				printf("器材所在柜行(柜子的行和抽屉)：%d\n",$searchRecords[$equip_id-1][6]);

			}
		?>
</body>
</html>