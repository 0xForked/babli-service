<?php

  require_once '../_include/Models.php';

  $mod = new Models();

  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents("php://input"));

    if(isset($data -> operation)){

     $operation = $data -> operation;

     if(!empty($operation)){

        if($operation == 'register'){

           if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> name)
              && isset($data -> user -> email) && isset($data -> user -> password)){

              $user = $data -> user;
              $name = $user -> name;
              $email = $user -> email;
              $password = $user -> password;

            if ($mod -> isEmailValid($email)) {

              echo $mod -> registerUser($name, $email, $password);

            } else {

              echo $mod -> getMsgInvalidEmail();
            }

           } else {

              echo $mod -> getMsgInvalidParam();

           }
  
        }else if ($operation == 'login') {

          if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> email) && isset($data -> user -> password)){

            $user = $data -> user;
            $email = $user -> email;
            $password = $user -> password;

            echo $mod -> loginUser($email, $password);

          } else {

            echo $mod -> getMsgInvalidParam();

          }
        } else if ($operation == 'chgPass') {

          if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> email) && isset($data -> user -> old_password)
            && isset($data -> user -> new_password)){

            $user = $data -> user;
            $email = $user -> email;
            $old_password = $user -> old_password;
            $new_password = $user -> new_password;

            echo $mod -> changePassword($email, $old_password, $new_password);

          } else {

            echo $mod -> getMsgInvalidParam();

          }
        }
     }else{

        echo $mod -> getMsgParamNotEmpty();

     }
    } else {

        echo $mod -> getMsgInvalidParam();

    }
  } else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    //asd
    echo "<center>Login API</center>";

  }
?>
