<!--数据库访问接口文件-->
<!--函数一：连接数据库connecMySQL()
    函数二：关闭数据库closeMySQL($connect)
    函数三：查询器材的id、name、prepar、residue、location  searchAllData($SQL)
    函数四：借出器材borrow($account_id,$equip_id)
-->

<?php
    //函数一：连接数据库houtai--------------------------------------------------------------------------------
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

    //函数二：关闭数据库--------------------------------------------------------------------------------
         function closeMySQL($connect){
                mysqli_close($connect);
         }


    //函数三：查询qicaiku器材信息：id、name、prepar、residue、location------------------------------------------------------------
         function searchAllData($SQL)
        {
                if($SQL==''){
                    //没有任何输入表示查询全部内容
                        $sql="select * from qicaiku ";
                        $con=connecMySQL();
                        $result=mysqli_query($con,$sql);
                }
                else{
                    //like查询
                        $sql="select * from qicaiku where name like '%".$SQL."%' ";
                        $con=connecMySQL();
                        $result=mysqli_query($con,$sql);
                }
            
                 //构造要返回的查询结果，方便返回到index.php使用
                $records=array();
                $i=0;
                $searchRecords=[];
                while($record=mysqli_fetch_array($result)){
                    $records[]=$record;
                    $searchRecords[$i][0]=$records[$i]['id'];
                    $searchRecords[$i][1]=$records[$i]['name'];
                    $searchRecords[$i][2]=$records[$i]['prepar'];
                    $searchRecords[$i][3]=$records[$i]['residue'];
                    $searchRecords[$i][4]=$records[$i]['located_room'];
                    $searchRecords[$i][5]=$records[$i]['located_cabinet'];
                    $searchRecords[$i][6]=$records[$i]['located_line'];
                    $i=$i+1;
                }
                closeMySQL($con); 
                
               return $searchRecords;
        }

    //函数四：借出器材----------------------------------------------------------------------------------------
        function borrow($account_id,$equip_id,$lend_number,$in_storage_time)
                {
                 //构造一个sql语句，修改borrow_return_form表格，执行借出出库记录
            if($lend_number>0){$state=1;}
            else{$state=-1;}
                    $sql="insert into borrow_return_form(account_id,equip_id,lend_number,in_storage_time,state) 
            values(".$account_id.",".$equip_id.",".$lend_number.",'".$in_storage_time."',state=".$state.");";
    
                    $con=connecMySQL();
                    $result=mysqli_query($con,$sql);

                 //修改shiwaitable的仓库剩余数量residue，执行借出出库   
                    if($result){ 
                        echo "借阅记录生成。";
                        $sql_3="update qicaiku set residue=residue-".$lend_number." where id=".$equip_id.";";
                        $updateResult=mysqli_query($con,$sql_3);

                        if($updateResult){echo "仓库剩余修改成功\n";}
                        else{
                            echo "出库失败，联系管理员";
                        //在borrow_return_form里，标记state为operat_false,也就是state=0.
                            //$sql_4="update borrow_return_form set state=0 where ";
                            //mysqli_query($con,$sql_4);
                        //恢复对器材库借出出库的操作
                            //$sql_5="update qicaiku set residue=residue+".$lend_number." where id=".$equip_id.";";;
                            //mysqli_query($con,$sql_5);
                            }
                    }else{
                        echo "借阅失败,联系管理员";
                        //$sql_4="update borrow_return_form set state=0";
                        //mysqli_query($con,$sql_4);//在borrow_return_form里，标记state为operat_false,也就是state=0.
                    }

                     closeMySQL($con);
                     return $result;//返回borrow_return_form修改成功。其实应该返回的是result和updateResult，用于看所有事情是不是都成功了。
                }




?>
