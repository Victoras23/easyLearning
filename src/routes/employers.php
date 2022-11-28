<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReallySimpleJWT\Token;
use Slim\Factory\AppFactory;



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
        // $data = array(
        //     "username" => $username,
        //     "first_name" => $first_name,
        //     "last_name" => $last_name,
        //     "password" => $password
        // );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(500);
    }

});




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