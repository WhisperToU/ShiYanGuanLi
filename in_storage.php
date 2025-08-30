<!--====这个文件实现通过卡片的形式依次入库照片、位置、数量、prepar信息。=====-->
<!--实现这个目标要分成3步：先访问数据库；再设置每次提交的初始默认值；最后提交内容-->
<!--为什么要访问数据库呢。因为要修改的是哪个器材的内容，需要id定位。这个id完全可以是一次加1默认的，这样就是实现了遍历整个仓库所有器材的目的。-->
<!--为什么要设置每次提交的初始默认值呢。因为第一次进入页面的时候必须要初始化id，才知道从哪里开始遍历整个仓库。-->
<!--最后提交以form的形式。点击提交按钮之后，会跳转到formSubmit.php页面，表示提交成功。-->

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href=".\in_storage.css">
	<title>入库位置与剩余信息</title>
</head>
<body style="text-align: center;">
<!---=====提交表单实现，一共分为3个部分======-->
<!--首先，用php查询数据库所有信息，并返回-->
<!--然后，使用php数据进行提交表格的渲染-->
	<!--第1部分：查询数据库所有信息,全部返回-->
		<?php
			require_once('.\searchAllData.php');
			$searchRecords=searchAllData();
		?>
	<!--第2部分：接收提交成功后的返回值，设置下次提交的初始值，如：id、name、image-->
		<?php 
			if(!empty($_POST['ID_begin'])){
				$id=htmlspecialchars($_POST['ID_begin']);
				$name=$searchRecords[$id-1][1];
				$image="..\\resource\\".$id.".avif";
			}
			else {
				$id=htmlspecialchars($_POST['id']);
				$id+=1;
				$name=$searchRecords[$id-1][1];
				$image="..\\resource\\".$id."\.avif";
			}
		?>		
	<!--第3部分：提交页面设计-->
		<img src="<?php echo $image;?>" style="width: 220px;height: 220px;direction: block;"/><br>

		<form action="./formSubmit.php" method="post" name="form">

			<input style="text" value="<?php echo $id;?>" name="id" ><br/>
			<input style="text" value="<?php echo $name;?>" name="name" ><br/>

			<input style="text" placeholder="located_room" name="located_room" required>
			<br/>

			<input type="text" placeholder="located_cabinet" name="located_cabinet" required>
			<br/>

			<input type="text" placeholder="located_line" name="located_line"required>
			<br/>

			<input type="text" placeholder="residue" name="residue"required>
			<br/>

			<input type="text" placeholder="prepar" name="prepar">
			<br/>

		  <!-- 是否是耗材，单选按钮 -->
		    <label>是否是耗材:</label>
			    <div id="tb">
			    	<div id="td"><label for="1">是</label></div>
			    	<div id="td"><input type="radio" id="yes" name="consumables" value="1" checked></div>

				    <div id="td"><label for="0">不是</label></div>
			    	<div id="td"><input type="radio" id="no" name="consumables" value="0"></div>
					  
		   		 </div>
		   		 <br/>
			<input value="提交" type="submit" ><br>
</form>
<input type="button" value="返回首页" onclick="javascript:window.location.href='index.php'">
<br>




</body>
</html>
