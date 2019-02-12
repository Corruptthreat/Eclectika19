<?php
   
   
   
    $con =  mysqli_connect('localhost','eclectik_ketan','eclectika19','eclectik_project') or die('Cannot connect to the DB');
    
    $query="SELECT * FROM `eclectik_project`.`mapData`";

   $result= mysqli_query($con,$query)or die(mysqli_error($con));

    $posts = array();
                            if(mysqli_num_rows($result)) {
                            while($post = mysqli_fetch_assoc($result)) {
                              $posts[] = array('post'=>$post);
    }
  }
              /* output in necessary format */
   
            header('Content-type: application/json');
            echo json_encode(array('posts'=>$posts));
  
 
           /* disconnect from the db */
           @mysqli_close($link);

    
     mysqli_close($con);
 


?>