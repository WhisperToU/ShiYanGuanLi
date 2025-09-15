<!--====这个文件 处理来自fresh2.php的提交要求，并初始化下一次的更新要求返回给fresh2.php=====-->
<!--文件分成4个部分：接收fresh2.php传过来参数；提交给数据库update；构造update过程中连接数据库需要的函数；初始化下次更新要求 并返回给fresh2.php-->
<?php
 //第1部分：fresh2.php form传过来的参数
	$located_room=htmlspecialchars($_POST['located_room']);
	$located_cabinet=htmlspecialchars($_POST['located_cabinet']);
	$located_line=htmlspecialchars($_POST['located_line']);
	$residue=htmlspecialchars($_POST['residue']);
	$prepar=htmlspecialchars($_POST['prepar']);
	$consumables=htmlspecialchars($_POST['consumables']);
	$id=htmlspecialchars($_POST['id']);
    $name=htmlspecialchars($_POST['name']);

//第2部分：提交给数据库，update
	$sql="update qicaiku set located_room=".$located_room.",located_cabinet=".$located_cabinet.",located_line=".$located_line.",residue=".$residue.",consumables=".$consumables." where id=".$id.";";
    $con=connecMySQL();
    $result=mysqli_query($con,$sql);
    mysqli_close($con);

//第3部分：update过程中需要被调用的自定义函数
    //函数一：连接数据库
        function connecMySQL(){
            define('server', 'localhost');
            define('user', 'root');
            define('password', 'root');
            define('database', 'lab_management');
            
            $connect=mysqli_connect(server,user,password,database);
            //判断是否连接上
            if(!$connect){
                die('数据库连接失败。'.mysqli_connect_error());
            }
            $connection='数据库连接成功。';
            return $connect;
         }
   //函数二：关闭数据库
         function closeMySQL($connect){
            mysqli_close($connect);
            echo "数据库关闭";
        }

?>

<!--第4部分：初始话下次需要更新的要求，点击按钮返回给fresh2.php-->
    <form action="./in_storage.php" method="post" name="form">
        已经更新ID：<input style="text" value="<?php echo $id;?>" name="id" ><br/>
        <div>已经更新名字：<?php echo $name;?></div><br>
        <input value="点击返回" type="submit" style="width:100%;height: 90px;font-size: 60px;">
    </form>