<?php  
  
  {             
 
      
                                                               /* connect to the db */
                $link =  mysqli_connect('localhost','eclectik_ketan','eclectika19','eclectik_project') or die('Cannot connect to the DB');
    
                                                     /* grab the posts from the db */
                                                     
                                                     $imgurl=$_GET['imgUrl']."&alt=media";
 
                             $query = "INSERT INTO `eclectik_project`.`userData` (`latitude`, `longitude`, `imgUrl`, `description`,`checkData`, `locality`) VALUES ('".$_GET['lat']."', '".$_GET['lon']."', '".$imgurl."', '".$_GET['description']."', '".$_GET['checkData']."', '".$_GET['locality']."');";
                             $result = mysqli_query($link,$query) or die('Errant query:  '.$query);


                     if(mysqli_affected_rows($link))
                     echo "sucess";
                     else
                     echo "error";
                    
                    
                          mysqli_close($link);
                        
           
           
      
  } 
?>
 