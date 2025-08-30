<!--这个文件实现：输入关键词，查询数据库，将图片显示在浏览器上。-->
<!--===实现这个目标，分成2个部分==-->
<!--===第1部分：布局显示  、 调用查询数据库==-->
<!--===第2部分：计算图片的位置，以帮助第1部分显示==-->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href=".\index.css">
	<title>shiwai.com</title>
</head>
<body>
<!--===第1部分：布局显示 、 调用查询数据库===-->
<!--===布局显示：主要是固定在屏幕上的搜索栏 和 一个瀑布流显示搜索结果===-->
<!--===调用数据库：用php获取form中的输入信息，然后调用mysql.php进行查询===-->
	<div id="topWhite"></div>
<!--头部搜索框-->
	<div id="cover">
	  <form method="post" action="">
	    <div class="tb">
	      <div class="td">
	        <input type="text" placeholder="雅安成实外 实验室" name="searchText"></div>
		        <?php
					// 初始化 $sql 变量
					$sql = '';

					// 检查用户是否提交了搜索表单（即 'searchText' 是否存在）
					if (isset($_POST['searchText'])) {
						$sql = $_POST['searchText'];
					}

					// 引入数据库访问文件
					require_once('.\mysql_access.php');

					// 调用查询函数，传入 $sql（可能是关键词，也可能是空字符串）
					$searchRecords = searchAllData($sql);
				?>
	      <div class="td" id="s-cover">
	        <button type="submit">
	          <div id="s-circle"></div>
	          <span></span>
	        </button>
	      </div>
	    </div>
	  </form>
	</div>
<!--主题瀑布流显示-->
<div id="box"></div>

	<!-- ===第2部分：计算要显示的图片的位置===-->
	<!--在script中使用innerHTML可以执行显示任务，所以第2部分的核心是计算图片的位置-，再调用innerHTML进行显示-->
	<!--由于数据库的图片大小都十分规范：220*220px，所以位置计算很方便，只需再考虑个图片间隔。图片间隔是为了美化考虑。-->
	<!--由于想设计成随着浏览器的宽度 图片显示的列数随机变化的样子，所以还要计算列数和行数。-->
	<!--列数=浏览器宽度/(图片宽度+图片间隔)；行数=图片总数/列数；-->
	<!--由于加载速度挺快的，所以没有使用懒加载-->
	<script>
//首先构造3个函数：瀑布流计算函数waterFall();client兼容性函数；scroll兼容性函数
//然后在windows.onload()里面调用

		var box = document.getElementById('box');
		var searchRecords=<?php echo json_encode($searchRecords); ?>;//初始化图片总数,用于计算要显示的行数（数据来自第1部分的查询结果）
		var gap = 10;// 定义每一列之间的间隙 为10像素
       //document.write(searchRecords[1]);//debug专用

		window.onload = function() {
			waterFall(searchRecords);
			
        // 页面尺寸改变时实时触发
			window.onresize = function() {  waterFall(searchRecords); };

		};//这个分号可有可无。有的话，onload函数会被当作语句执行；没有的话，onload函数本身就会默认被优先首先执行。


    //函数一：waterFall()计算图片位置，构造innerHTML，形成瀑布流
		function waterFall(searchRecords) {
            // 1、确定列数  = 页面的宽度 / 图片的宽度，
				var pageWidth = getClient().width;
				var itemWidth = 220;//这里应该取图片css宽度，但是要根据是手机还是电脑而定
				var columns = parseInt(pageWidth / (itemWidth + gap));
				var searchNum=searchRecords.length;
			//2、确定行数=图片总数/列数，图片总数就是查询结果数，
				var rows=parseInt(searchNum/columns)+1;
			//3、计算每一张图片的位置，并且用innerHTML输出到浏览器，
				//初始化第一张图片的位置变量
				var imageLeft=0;
				var imageTop=0;
				var innerHTML=[];
				//循环计算位置，并构造innerHTML
				for(var j=0;j<rows;j++){
					for(var i=0;i<columns;i++){
						imageLeft=(itemWidth + gap) * i;

						var imageName=new Number(j*columns+i);//图片的命名规则是序号。图片创建于mysql.php访问数据库之后。
						if(imageName<searchNum){
							console.log(imageName);
								imgSrc="<img src='image/equipment/"+searchRecords[imageName][0]+".avif' />";

								//点击瀑布中的卡片，跳转到借阅预约页面
								borrowJumpStart="<div  onclick=\"javascript:window.location.href='borrow_return.php?account_id=1&equip_id="+searchRecords[imageName][0]+"&residue="+searchRecords[imageName][3]+"'\">";
								borrowJumpEnd="</div>";

								divStart="<div class='item' style='top:"+imageTop+"px;left:"+imageLeft+"px' >";
								divEnd="</div>";
								
								divInnerStart="<div class='item_inner'>"+searchRecords[imageName][1]+"<br/><span class='item_inner_prepar1'>注意：</span><span class='item_inner_prepar2'>"+searchRecords[imageName][2]+"<br/></span><span>仓库剩余："+searchRecords[imageName][3]+"</span>";//根据mysql.php查询结果，1为name，2为prepar
								divInnerEnd="</div>";

								innerHTML+=borrowJumpStart+divStart+imgSrc+divInnerStart+divInnerEnd+divEnd+borrowJumpEnd;		
							}
							else break;
						}
						imageTop=imageTop+355;//距顶部的距离=图片高度+图片下字的高度+图片间间隔	=280px
					}
					box.innerHTML=innerHTML;
				}
    // 函数二：clientWidth 处理兼容性
		function getClient() {
			return {
				width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
				height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
			}
		}
    // 函数三：scrollTop兼容性处理
		function getScrollTop() {
			return window.pageYOffset || document.documentElement.scrollTop;
		}		


	</script>



</body>
</html>