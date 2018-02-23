<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'test');
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
  
  
$module = mysqli_real_escape_string($db, $_REQUEST['module']);
$data=array();


/**************Register New User Start******************/

if($module=='add_user')
{
	$db = $GLOBALS['db'];
	
	$name		=	mysqli_real_escape_string($db, $_REQUEST['name']);	
	$email		=	mysqli_real_escape_string($db, $_REQUEST['email']);	
	$mobile		=	mysqli_real_escape_string($db, $_REQUEST['mobile']);
	$dob		=	mysqli_real_escape_string($db, $_REQUEST['dob']);	
	$password	=	mysqli_real_escape_string($db, $_REQUEST['password']);	
	
	  if( !empty($name)  && !empty($email)  && !empty($dob) ){
		  
			$que = mysqli_query($db,"SELECT * FROM users WHERE email = '$email'");
			$user= mysqli_fetch_object($que);
			$u_id = $user->id;
			if(mysqli_num_rows($que)<1){	
				$que = "INSERT INTO users(name, email, mobile, dob, password) 
				VALUES ('$name','$email','$mobile','$dob','$password')";
				mysqli_query($db,$que);
				echo '   User Successfully Added';
			
			}else{
				mysqli_query($db,"UPDATE users SET name='$name',email='$email',mobile='$mobile',password='$password' WHERE id = '$u_id'");
				echo 'User Successfully Update';
			}	  
	  }
}

/**************Register New User Start******************/


/********Login API***********/
if($module=='loginuser')
{
	$email		=	mysqli_real_escape_string($db,$_REQUEST['email']);
	$password		= 	mysqli_real_escape_string($db,$_REQUEST['password']);
	if($_REQUEST['email']!='' || $_REQUEST['password']!='') {

	$sql="SELECT * FROM users WHERE email='$email' and password='$password' ";

    $result=mysqli_query($db,$sql);
    $count=mysqli_num_rows($result);
	$row=mysqli_fetch_object($result);   
	$i=1;
    if($count>0){         
	
		$data['username']= $row->name;
		$data['email']= $row->email;
		$data['mobile']= $row->mobile;
		$data['dob']= $row->dob;	
    }   
	
}
if(empty($data)){
		$data = null;
	}
	returnJsonData(false,"Ok",$data);
}


#json return function
function returnJsonData($err,$msg,$result){
    header('Content-type: application/json');

	$data['error']=$err;
	$data['message']=$msg;
	if(is_array($result)){
		$data['result']=$result;
	}else{
		$data['result']=array($result);
	}

	echo json_encode( $data );

    exit();
}

?>