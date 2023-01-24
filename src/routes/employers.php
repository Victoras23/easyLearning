<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReallySimpleJWT\Token;
use Slim\Factory\AppFactory;


///////////////////////////////////////////
//authentication
///////////////////////////////////////////


$app->post('/login',function(Request $request , Response $response ){
    $username = $request->getParam('username');
    $password = $request->getParam('password');

    $sql2 = "SELECT user_id FROM users_data WHERE user_name = '$username'";
    $sql1 = "SELECT password FROM users_data WHERE user_name = '$username'";

    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql1);
        $stmt->execute();
		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

        
        $message = true;
        
		if( !$result ) {
            $message = false;
		}
		if( !password_verify( $password, $result["password"] ) ) {
            $message = false;
		}
        if ($message){
            $stmt = $connection->prepare($sql2);
            $stmt->execute();
            $user_id = $stmt->fetch( \PDO::FETCH_ASSOC );

            $date = new DateTime('tomorrow');
            $date = $date->format('Y-m-d');

            $name = password_hash($username ,PASSWORD_DEFAULT ) ;
            $password = password_hash($password ,PASSWORD_DEFAULT );
            $hashed = password_hash($result["password"] ,PASSWORD_DEFAULT );
            $token = $name.$password.$hashed;

            $sql3 = "UPDATE tokens SET token = '$token' , expiration_time = '$date' WHERE user_id = $user_id[user_id]";

            $stmt = $connection->prepare($sql3);
            $stmt->execute();
        }

        $result=array(
            "token"=>$token
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
});

$app->post('/register',function(Request $request , Response $response ){
    $username = $request->getParam('username');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $password = $request->getParam('password');
    $password_hashed = password_hash($password ,PASSWORD_DEFAULT );
    
    $sql = "INSERT INTO users_data (user_name,first_name,last_name,password) VALUE (:user_name,:first_name,:last_name,:password) ";

    $sql1 = "SELECT user_id FROM users_data WHERE user_name = '$username'";

    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':user_name',$username);
        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':last_name',$last_name);
        $stmt->bindParam(':password',$password_hashed);
        $result = $stmt->execute();

        $stmt = $connection->prepare($sql1);
        $stmt->execute();
		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

        $sql2 = "INSERT INTO tokens (user_id) VALUE ($result[user_id]) ";
        $stmt = $connection->prepare($sql2);
        $stmt->execute();

        $db=null;

        $result=array(
            "status"=>"ok",
            "rows"=>$result,
            "hash"=>$password_hashed
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
});

$app->post('/validation_username',function(Request $request , Response $response ){
    
    $username = $request->getParam('user_name');

    // $sql1 = "SELECT user_name FROM users_data WHERE user_name = '$username' ";
    $sql1 = "SELECT COUNT(*) FROM users_data WHERE user_name = '$username'";
    //CAST( CASE WHEN 
    // > 0 THEN 1 ELSE 0 END AS BIT) 
    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->query($sql1);
        $test = $stmt->fetchAll(PDO::FETCH_OBJ);

        $result=array(
            "rows"=>$test
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }

});

$app->post('/AddCourse',function(Request $request , Response $response ){

    $userID = $request->getParam('userID');
    $course_name = $request->getParam('course_name');
    $course_description = $request->getParam('course_description');

    $sql1 = "INSERT INTO `courses`( `user_id`, `course_name`, `course_description`) values( :userID, :course_name, :course_description) ";
    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql1);

        $stmt->bindParam(':userID',$userID);
        $stmt->bindParam(':course_name',$course_name);
        $stmt->bindParam(':course_description',$course_description);

        $result1 = $stmt->execute();

        $result=array(
            "status"=>"ok",
            "rows"=>$result1
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }

});

$app->post('/DeleteCourse',function(Request $request , Response $response ){

    $userID = $request->getParam('userID');
    $courseID = $request->getParam('courseID');

    $sql1 = "DELETE FROM courses WHERE course_id= $courseID and user_id = $userID ";
    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql1);
        $result1 = $stmt->execute();

        $result=array(
            "status"=>"ok",
            "rows"=>$result1
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }

});

$app->post('/AddLesson',function(Request $request , Response $response ){

    $userID = $request->getParam('userID');
    $courseID = $request->getParam('courseID');
    $name = $request->getParam('name');
    $data = $request->getParam('data');
    $description = $request->getParam('description');

    $result1 = 0;

    $sql1 = "SELECT count(*) FROM courses WHERE user_id = '$userID' and course_id = '$courseID' ";
    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql1);
        $result1 = $stmt->execute();
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
    if ($result1 != 0)
    {
        $sql2 = "INSERT INTO course_content(course_id, location, public_name, lesson_description) values (:userID, :data, :name, :descritpion)";
        try
        {
            $db = new DataBase();
            $connection = $db->connect();

            $stmt = $connection->prepare($sql2);
            $stmt->bindParam(':userID',$userID);
            $stmt->bindParam(':data',$data);
            $stmt->bindParam(':name',$name);
            $stmt->bindParam(':descritpion',$description);
            $result2 = $stmt->execute();


            $result=array(
                "status"=>"ok",
                "rows"=>$result2
            );

            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type','application/json')
                ->withStatus(200);

        }
            catch(PDOException $e){
            $error = array(
                "message"=>$e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type','application/json')
                ->withStatus(500);
    }
    }
});

$app->delete ('/DeleteLesson',function(Request $request , Response $response , array $args){
    $course_id = $request->getParam('courseID');
    $lesson_id = $request->getParam('lessonID');  
    
    $sql = "DELETE FROM course_content WHERE course_id = $course_id AND lecture_id = $lesson_id";

    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql);
        $result = $stmt->execute();

        $db=null;

        $result=array(
            "status"=>"ok",
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
});

$app->post('/EditCourse',function(Request $request , Response $response ){
    
    $user_id = $request->getParam('userID');
    $course_id = $request->getParam('courseID');
    $course_name = $request->getParam('course_name');
    $course_description = $request->getParam('course_description');

    $sql1 = "UPDATE courses SET course_name = '$course_name', course_description = '$course_description' WHERE course_id = $course_id and user_id = $user_id";

    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->query($sql1);
        $test = $stmt->fetchAll(PDO::FETCH_OBJ);

        $result=array(
            "status" => "ok",
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }

});

$app->post('/EditLesson',function(Request $request , Response $response ){

    $course_id = $request->getParam('courseID');
    $lesson_id = $request->getParam('lessonID');
    $location = $request->getParam('location');
    $lesson_name = $request->getParam('lessonName');
    $lesson_description = $request->getParam('lessonDescription');

    $sql1 = "UPDATE course_content SET location = '$location', public_name = '$lesson_name', lesson_description = '$lesson_description' WHERE course_id = $course_id and lecture_id = $lesson_id";

    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->query($sql1);
        $test = $stmt->fetchAll(PDO::FETCH_OBJ);

        $result=array(
            "status" => "ok",
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
    catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }

});

$app->post('/addNewSubscription', function (Request $request, Response $response) {
    $user_id = $request->getParam('userID');
    $course_id = $request->getParam('courseID');
    $active = true;
    $progress = 0;

    $sql = "INSERT IGNORE INTO subscriptions (progress, user_id, course_id, active) 
            VALUES (:progress,:user_id, :course_id, :active) ON DUPLICATE KEY UPDATE active = true;";

    try {
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':progress',$progress);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':course_id',$course_id);
        $stmt->bindParam(':active',$active);
        $result = $stmt->execute();

        $result=array(
            "rows"=>$result,
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
});

$app->delete('/deleteASubscription', function (Request $request, Response $response, array $args) {
    $user_id = $request->getParam('userID');
    $course_id = $request->getParam('courseID');

    $sql = "UPDATE subscriptions SET active=false WHERE user_id= $user_id and course_id= $course_id";

    try {
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->query($sql);
        $test = $stmt->fetchAll(PDO::FETCH_OBJ);

        $result=array(
            "status" => "ok",
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message"=>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
});

$app->put('/UpdateProgress', function(Request $request, Response $response) {
    $user_id = $request->getParam('userID');
    $course_id = $request->getParam('courseID');
    $progress = $request->getParam('progress');

    $sql = "UPDATE subscriptions SET progress=$progress WHERE user_id= $user_id and course_id= $course_id";


    try{
        $db = new DataBase();
        $connection = $db->connect();

        $stmt = $connection->query($sql);
        $test = $stmt->fetchAll(PDO::FETCH_OBJ);

        $result=array(
            "status" => "ok",
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    } catch(PDOException $e){
        $error = array(
            "message"=>$e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }
});



/////////////////////////////////////////////////////////////
//examples
/////////////////////////////////////////////////////////////




// $app->delete ('/delete/{id}',function(Request $request , Response $response , array $args){
//     $id = $args['id'];
    
//     $sql = "DELETE FROM employers WHERE id=$id";
//     $sql1 = "SELECT * FROM employers";

//     try{
//         $db = new DataBase();
//         $connection = $db->connect();

//         $stmt = $connection->prepare($sql);
//         $result = $stmt->execute();

//         $stmt = $connection->query($sql1);
//         $employers = $stmt->fetchAll(PDO::FETCH_OBJ);

//         $db=null;

//         $result=array(
//             "status"=>"ok",
//             "rows"=>$employers
//         );

//         $response->getBody()->write(json_encode($result));
//         return $response
//             ->withHeader('content-type','application/json')
//             ->withStatus(200);
//     }
//     catch(PDOException $e){
//         $error = array(
//             "message"=>$e->getMessage()
//         );
//         $response->getBody()->write(json_encode($error));
//         return $response
//             ->withHeader('content-type','application/json')
//             ->withStatus(500);
//     }
// });

// $app->post('/update',function(Request $request , Response $response ){
//     $id = $request->getParam('id');
//     $first_name = $request->getParam('first_name');
//     $last_name = $request->getParam('last_name');
//     $phone = $request->getParam('phone');

//     $sql = "SELECT * FROM employers WHERE id=$id";
    
//     $sql1 = "UPDATE employers SET first_name = :first_name, last_name =:last_name, phone=:phone WHERE id=$id";

//     $sql2 = "SELECT * FROM employers WHERE id=$id";

//     try{
//         $db = new DataBase();
//         $connection = $db->connect();

//         $stmt = $connection->query($sql);
//         $employers = $stmt->fetchAll(PDO::FETCH_OBJ);

//         $stmt = $connection->prepare($sql1);

//         $stmt->bindParam(':first_name',$first_name);
//         $stmt->bindParam(':last_name',$last_name);
//         $stmt->bindParam(':phone',$phone);

//         $result = $stmt->execute();

//         $stmt = $connection->query($sql2);
//         $employers1 = $stmt->fetchAll(PDO::FETCH_OBJ);

//         $db=null;
//         $result=array(
//             "status"=>"ok",
//             "before change"=>$employers,
//             "after change"=>$employers1
//         );
//         $response->getBody()->write(json_encode($result));
//         return $response
//             ->withHeader('content-type','application/json')
//             ->withStatus(200);
//     }
//     catch(PDOException $e){
//         $error = array(
//             "message"=>$e->getMessage()
//         );
//         $response->getBody()->write(json_encode($error));
//         return $response
//             ->withHeader('content-type','application/json')
//             ->withStatus(500);
//     }
// });