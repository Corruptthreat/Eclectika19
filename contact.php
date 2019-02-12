<?php

 
    $sender_name = $_POST['myform']; 
    $myfile = fopen("messages/$sender_name.txt", "w");
    fwrite($myfile, $sender_name);
    echo "success";
?>