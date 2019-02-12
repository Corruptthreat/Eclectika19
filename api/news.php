<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../include/db.php';
$app = new \Slim\App;

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");


//GetSchemes
$app->get('/GetAllUsers', function (Request $request, Response $response) {


   $sql = "SELECT * FROM usertable";

    try{
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

        $scheme = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($scheme);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});



$app->get('/GetUserById', function (Request $request, Response $response) {
     $imei =$_GET['imei'];

   $sql = "SELECT * FROM UserTable  WHERE IMEI='$imei'";

    try{
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

        $scheme = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($scheme);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});


// User Login

$app->post('/UserLogin', function (Request $request, Response $response) {

    $acc_token = $request->getParam('acc_token');
    $phone_number = $request->getParam('phone_number');
    $imei = $request->getParam('imei');

     
   
    $sql = "INSERT IGNORE INTO UserTable  (Acc_Token,Phone,IMEI) VALUES (:acc_token,:phone_number,:imei)";


    try{
        $db = new db();

        $db= $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam('acc_token',$acc_token);       
        $stmt->bindParam('phone_number',$phone_number);
        $stmt->bindParam('imei',$imei);

        

        $stmt->execute();



     $sql2 = "SELECT EC_Id FROM UserTable WHERE IMEI='$imei'";

      $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql2);

        $scheme = $stmt->fetchAll(PDO::FETCH_OBJ);



  
          echo json_encode($scheme);



    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});


// Get All Events

$app->get('/GetAllEvents', function (Request $request, Response $response) {

    
    $UserId = $_GET['user_id'];
   

   $sql = "SELECT *,Categories.Category_Name FROM Events INNER JOIN Categories ON Events.Category_id=Categories.id";

    try{
        $response = array();
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {

            $product = array();
            $abc=$row['Id'];
            $product['Id']= $row['Id'];
            $product['Name']= $row['Name'];
            $product['Category_id']= $row['Category_id'];
            $product['Judging']= $row['Judging'];
            $product['Description']= $row['Description'];
            $product['Registration_Count']= $row['Registration_Count'];
            $product['Rules']= $row['Rules'];
            $product['Register']= $row['Register'];
            $product['Category_Name']= $row['Category_Name'];
            $product['ImageUrl']= $row['ImageUrl'];

               $sql2 = "SELECT UserId,EventId FROM Register WHERE UserId='$UserId' AND EventId='$abc'";

               $db2 = new db();

            $db2= $db2->connect();

           $stmt2 = $db2->query($sql2);


          if($stmt2->fetchColumn()>0){
                 
                 $product['isRegistered']=true;

             }else{
                
                $product['isRegistered']=false;
             
             }


        array_push($response, $product);

            

}
        echo json_encode($response);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});



$app->get('/GetRegisteredEvents', function (Request $request, Response $response) {

    
    $UserId = $_GET['user_id'];
   

   $sql = "SELECT * FROM Events INNER JOIN Categories ON Events.Category_id=Categories.id INNER JOIN Register ON Register.EventId=Events.Id WHERE Register.UserId=$UserId";

    try{
        $response = array();
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {

            $product = array();
            $product['EventId']= $row['EventId'];
            $product['Name']= $row['Name'];
            $product['Category_id']= $row['Category_id'];
            $product['Judging']= $row['Judging'];
            $product['Description']= $row['Description'];
            $product['Registration_Count']= $row['Registration_Count'];
            $product['Rules']= $row['Rules'];
            $product['Register']= $row['Register'];
            $product['Category_Name']= $row['Category_Name'];                 
            $product['isRegistered']=true;

            
            


        array_push($response, $product);

            

}
        echo json_encode($response);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});




$app->get('/GetAllFace', function (Request $request, Response $response) {
    
    $UserId = $_GET['user_id'];
   $sql = "SELECT * FROM FOE";

    try{
        $response = array();
        $db = new db();

        $db= $db->connect();

        $stmt = $db->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
            $product = array();
            $abc=$row['Id'];
            $product['Id']= $row['Id'];
            $product['Name']= $row['Name'];
            $product['Image_Url']= $row['Image_Url'];
            $product['LikesCount']= $row['LikesCount'];
            $product['Time']= $row['Time'];
            $sql2 = "SELECT LikedBy,FoeId FROM Likes WHERE LikedBy='$UserId' AND FoeId='$abc'";
            $db2 = new db();

            $db2= $db2->connect();

           $stmt2 = $db2->query($sql2);


          if($stmt2->fetchColumn()>0){
                 
                 $product['isLiked']=true;

             }else{
                
                $product['isLiked']=false;
             
             }


        array_push($response, $product);
}
        echo json_encode($response);
        
    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});



$app->post('/LikeFace', function(Request $request, Response $response){
    $UserId = $request->getParam('user_id');
    $foe_id = $request->getParam('foe_id');
    $is_liked = $request->getParam('is_liked');
    //if is_liked=0 like= like+1 
    //if is_liked=1 like=like-1
    if($is_liked==0){
        $sql = "INSERT INTO Likes(LikedBy, FoeId)
            VALUES ($UserId, $foe_id)";
        try{
            $db = new db();

            $db= $db->connect();

            $stmt = $db->prepare($sql);
            $stmt->execute();
            $sql2 = "SELECT LikesCount FROM FOE 
                    WHERE Id = $foe_id";
        
            $db2 = new db();
            $db2= $db2->connect();
            $stmt2 = $db2->query($sql2);
            $scheme = $stmt2->fetch(PDO::FETCH_ASSOC);
            // print_r($scheme);
            // echo '$scheme';
            $like = $scheme['LikesCount'];
            $like = $like +1;
            $sql3 = "UPDATE FOE
                    SET LikesCount = $like
                    WHERE Id = $foe_id";
            $db3 = new db();

            $db3= $db3->connect();

            $stmt3 = $db3->prepare($sql3);
            $stmt3->execute();

              echo '{"error": false}';
          }catch (PDOException $exception){

            echo '{"error": '.$exception->getMessage().'}';
        }    
    }else{
        $sql = "DELETE FROM Likes
            WHERE LikedBy = $UserId AND FoeId = $foe_id";
        try{
            $db = new db();

            $db= $db->connect();

            $stmt = $db->prepare($sql);
            $stmt->execute();

            $sql2 = "SELECT LikesCount FROM FOE 
                    WHERE Id = $foe_id";
        
            $db2 = new db();
            $db2= $db2->connect();
            $stmt2 = $db2->query($sql2);
            $scheme = $stmt2->fetch(PDO::FETCH_ASSOC);
            // print_r($scheme);
            // echo '$scheme';
            $like = $scheme['LikesCount'];
            $like = $like -1;
            $sql3 = "UPDATE FOE
                    SET LikesCount = $like
                    WHERE Id = $foe_id";
            $db3 = new db();

            $db3= $db3->connect();

            $stmt3 = $db3->prepare($sql3);
            $stmt3->execute();



            echo '{"error": false}';
        }catch (PDOException $exception){

            echo '{"error": '.$exception->getMessage().'}';
        }      
    }
    
});



$app->post('/UserRegistration', function (Request $request, Response $response) {

    
    $UserId = $request->getParam('user_id');
    $user_name = $request->getParam('user_name');
    $college = $request->getParam('college');
    $branch = $request->getParam('branch');
    $semester = $request->getParam('semester');
    $email = $request->getParam('email');
    $ec_id= $request->getParam('ec_id');

   $sql = "UPDATE UserTable SET Name = '$user_name', college='$college',branch='$branch',Semester='$semester',email='$email',EC_Id='$ec_id' WHERE IMEI='$UserId'";

    try{
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

         $stmt->bindParam('Name',$user_name);
        $stmt->bindParam('College',$college);
        $stmt->bindParam('branch',$branch);
        $stmt->bindParam('Semester',$semester);
        $stmt->bindParam('email',$email);

        $stmt->execute();

       echo '{"error": false}';
    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});



// Event registration

$app->post('/EventRegister', function (Request $request, Response $response) {

    $user_id  = $request->getParam('user_id');
    $event_id = $request->getParam('event_id'); 
    
   
    $sql = "INSERT  INTO Register (UserId, EventId) VALUES ($user_id,$event_id)";


    try{
        $db = new db();

        $db= $db->connect();

        $stmt = $db->prepare($sql);        

        $stmt->execute();


    $sql = "UPDATE Events SET Registration_Count=Registration_Count+1 WHERE Id=$event_id";
    $stmt = $db->prepare($sql);  
    $stmt->execute();


         echo '{"error": false}';


    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});

// Event Login

$app->post('/EventLogin', function (Request $request, Response $response) {

    $organiser_id  = $request->getParam('organiserid');
    $password = $request->getParam('password'); 
    
   
    $sql = "SELECT * FROM Events INNER JOIN Categories ON Events.Category_id=Categories.id WHERE Events.Organiserid= $organiser_id AND Events.Password= $password ";
    


    try{
        $db = new db();

        $db= $db->connect();

        $stmt = $db->prepare($sql);        

        $stmt->execute();


       $count=0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
            $count++;

            $product = array();
            $product['Id']= $row['Id'];
            $product['Name']= $row['Name'];
            $product['Category_id']= $row['Category_id'];
            $product['Judging']= $row['Judging'];
            $product['Description']= $row['Description'];
            $product['Registration_Count']= $row['Registration_Count'];
            $product['Rules']= $row['Rules'];
            $product['Register']= $row['Register'];
            $product['Category_Name']= $row['Category_Name'];
            $product['error']= false;
            $product['isRegistered']=true;


      }


           if($count==0){
            $product = array();
           $product['error']= true;
          }

          echo json_encode($product);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});

// Registered user list


$app->get('/GetRegisteredUser', function (Request $request, Response $response) {
    
     $event_id =$_GET['eventid'];

   $sql = "SELECT * FROM Register INNER JOIN UserTable ON Register.UserId=UserTable.IMEI WHERE Register.EventId=$event_id ORDER BY Register.Id DESC";

    try{
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

        $scheme = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($scheme);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});


// Get All Events

$app->get('/GetEventById', function (Request $request, Response $response) {

    
    $UserId = $_GET['user_id'];
    $eventid= $_GET['event_id'];

   

   $sql = "SELECT *,Categories.Category_Name FROM Events INNER JOIN Categories ON Events.Category_id=Categories.id WHERE Events.Id='$eventid'";

    try{
        $response = array();
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {

            $product = array();
            $abc=$row['Id'];
            $product['Id']= $row['Id'];
            $product['Name']= $row['Name'];
            $product['Category_id']= $row['Category_id'];
            $product['Judging']= $row['Judging'];
            $product['Description']= $row['Description'];
            $product['Registration_Count']= $row['Registration_Count'];
            $product['Rules']= $row['Rules'];
            $product['Register']= $row['Register'];
            $product['Category_Name']= $row['Category_Name'];
            $product['ImageUrl']= $row['ImageUrl'];


               $sql2 = "SELECT UserId,EventId FROM Register WHERE UserId='$UserId' AND EventId='$abc'";

               $db2 = new db();

            $db2= $db2->connect();

           $stmt2 = $db2->query($sql2);


          if($stmt2->fetchColumn()>0){
                 
                 $product['isRegistered']=true;

             }else{
                
                $product['isRegistered']=false;
             
             }


        array_push($response, $product);

            

}
        echo json_encode($response);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});


// Get All Events

$app->get('/GetEventByCategory', function (Request $request, Response $response) {

    
    $category= $_GET['category'];

   

   $sql = "SELECT * FROM Events WHERE Category_id ='$category'";

    try{
        $response = array();
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {

            $product = array();
            $abc=$row['Id'];
            $product['Id']= $row['Id'];
            $product['Name']= $row['Name'];
            $product['Category_id']= $row['Category_id'];
            $product['Judging']= $row['Judging'];
            $product['Description']= $row['Description'];
            $product['Registration_Count']= $row['Registration_Count'];
            $product['Rules']= $row['Rules'];
            $product['Register']= $row['Register'];
           $product['ImageUrl']= $row['ImageUrl'];

            
        array_push($response, $product);

            

}
        echo json_encode($response);

    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});


$app->get('/GetAllSpons', function (Request $request, Response $response) {

    
   

   $sql = "SELECT * FROM Sponsers ORDER BY Id ASC";

    try{
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

        $scheme = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($scheme);
        
    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});


$app->get('/GetAllArtists', function (Request $request, Response $response) {

    
   

   $sql = "SELECT * FROM Artists ORDER BY Id ASC";

    try{
        
        $db = new db();

       $db= $db->connect();

        $stmt = $db->query($sql);

        $scheme = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($scheme);
        
    }catch (PDOException $exception){

        echo '{"error": '.$exception->getMessage().'}';
    }
});




$app->run();
