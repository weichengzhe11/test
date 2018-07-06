<?php
header("content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC'); 
try
{  
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
	$pdo=new PDO("mysql:host=localhost;dbname=test","root","",$options);
}
catch (PDOException $e)
{  
    die("fail to connect mysql".$e->getMessage());  
}

if(isset($_POST['submit']))
{ 
	//表单提交
	$group_id = isset($_POST['problem_group']) ? $_POST['problem_group'] : '1';
	$group_id =  intval($group_id); //分组id也就是哪一个调查问卷

	$mystr = "";

	//单选
	$mystr .= "1:".$_POST['myradio'];

    //多选
	if(empty($_POST['mycheckbox']))
	{ 
		echo "<script language=javascript>alert('多选必须选一个');history.back();</script>";
		exit;
    }
	else
	{ 
		$mystr .= ";2:".implode($_POST['mycheckbox'],',');
    }

	//填空
	$nosql = htmlspecialchars($_POST['nosql']);//第一个填空
	$db    = htmlspecialchars($_POST['db']);//第二个填空
	$mystr .= ";3:<".$nosql."><". $db.">" ;
    $mystr = addslashes($mystr);

	//时间
	$mytime = time();

    $pdo_prepare  = $pdo->prepare('insert into tp_problems_submit (group_id,submit_cont,intime) values (?,?,?)');
	$state = $pdo_prepare->execute(array($group_id,$mystr,$mytime));
	if($state)
	{ 
		echo "<script language=javascript>alert('成功');</script>";
    }
	else
	{ 
		echo "<script language=javascript>alert('失败');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<title>在线调查</title>
	</head>
<body>
	<form action="ttt.php" method="post" name="user_form" id="user_form">
		<input type="hidden" name="problem_group" value="20">
		A:我的单选测试1 <input type="radio" name="myradio" checked value="A">
		B:我的单选测试2 <input type="radio" name="myradio" value="B">
		<hr>
			A<input type="checkbox" name="mycheckbox[]" value="A" />
			B<input type="checkbox" name="mycheckbox[]" value="B" checked="checked" /> 
			C<input type="checkbox" name="mycheckbox[]" value="C" /> 
			D<input type="checkbox" name="mycheckbox[]" value="D"  /> 
		<hr>
		nosql你用过什么（<input type="text" name="nosql" />）你熟悉的数据库是（<input type="text" name="db" />）
		<hr>
		<input type="submit" name="submit" value="提  交">
	</form>
</body>
</html>
<?php
$pdo = null;