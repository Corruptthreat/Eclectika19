<?php  
  
  {             
 
      
                                                               /* connect to the db */
                $link =  mysqli_connect('localhost','eclectik_ketan','eclectika19','eclectik_project') or die('Cannot connect to the DB');
    
                                                     /* grab the posts from the db */
 
                             $query = "UPDATE userData SET checkData= 1 WHERE id=".$_GET['id'];
                             $result = mysqli_query($link,$query) or die('Errant query:  '.$query);


                     if(mysqli_affected_rows($link))
                     echo "sucess";
                     else
                     echo "error";
                    
                    
                          mysqli_close($link);
                        
           
           
      
  } 
?>