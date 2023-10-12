<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../src/vendor/autoload.php';
$app = new \Slim\App;

//endpoint get name
$app->get('/getName', function (Request $request, Response $response) {
    // Get the JSON data from the request body
    $requestData = json_decode($request->getBody(), true);

    // Make sure the JSON data is in the expected format
    if (isset($requestData['status']) && isset($requestData['data'])) {
        // Extract the data array from the request
        $data = $requestData['data'];

        $responsePayload = [
            'status' => 'success',
            'data' => $data,
        ];

        $response->getBody()->write(json_encode($responsePayload));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        // If the JSON data format is incorrect, return an error
        $errorPayload = [
            'status' => 'error',
            'message' => 'Invalid JSON data format in the request body',
        ];

        $response->getBody()->write(json_encode($errorPayload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});



//endpoint post name, insert data database "demo"
$app->post('/postName', function (Request $request, Response $response, array $args)
{
        $data=json_decode($request->getBody());
        $fname =$data->fname ;
        $lname =$data->lname ;
        //Database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "demo";
        try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",

        $username, $password);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE,

        PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO names (fname, lname)
        VALUES ('". $fname ."','". $lname ."')";
        // use exec() because no results are returned

        $conn->exec($sql);
        $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));

        } catch(PDOException $e){
        $response->getBody()->write(json_encode(array("status"=>"error",
        "message"=>$e->getMessage())));
        }
        $conn = null;
    return $response;
}); 

//endpoint printName
$app->post('/printName', function (Request $request, Response $response, array $args) {
    // Define the data as an associative array
    $data = [
        "status" => "success",
        "data" => [
            [
                "lname" => "hortizuela",
                "fname" => "manny"
            ],
            [
                "lname" => "licayan",
                "fname" => "arnold"
            ]
        ]
    ];

    // Send the response as JSON
    return $response->withJson($data);
});



//endpoint update name, update data database "demo"
$app->put('/updateName/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = json_decode($request->getBody());
    $fname = $data->fname;
    $lname = $data->lname;

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demo";

    try {
        // Create a PDO connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the name with the given ID in the database
        $sql = "UPDATE names SET fname = '$fname', lname = '$lname' WHERE id = $id";
        $conn->exec($sql);

        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }

    // Close the database connection
    $conn = null;

    return $response;
});


//endpoint delete name, delete data database "demo"
$app->delete('/deleteName/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "demo";

    try {
        // Create a PDO connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the name with the given ID from the database
        $sql = "DELETE FROM names WHERE id = $id";
        $conn->exec($sql);

        $response->getBody()->write(json_encode(array("status" => "success", "data" => null)));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array("status" => "error", "message" => $e->getMessage())));
    }

    // Close the database connection
    $conn = null;

    return $response;
});


$app->run();
?>