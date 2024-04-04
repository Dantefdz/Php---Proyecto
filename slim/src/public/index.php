<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::create();


//conexion a la base
function getConnection(){
    $dbhost = "localhost";
    $dbname = "inmobiliaria";
    $dbuser = "root";
    $dbpass = "";

    $connection = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
    $connection-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $connection;
}

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/tipos_propiedad/listar',function (Request $request, Response $response){
    $connection = getConnection();
    try{
        $query = $connection->query('SELECT nombre FROM tipo_propiedades');//Ejecuta la consulta a sql
        $tipos = $query->fetchAll(PDO::FETCH_ASSOC);//Trae resultados de la consulta a tipos
        
        $playload = json_encode([
            'status'=> 'success',
            'code' => 200,
            'data' => $tipos
        ]);
        $response->getBody()->write($playload);
        return $response->withHeader('Content-type','application/json');
    } catch(PDOException $e){
        $playload = json_encode([
            'status'=> 'success',
            'code' => 400
        ]);

        $response->getBody()->write($playload);
        return $response->withHeader('Content-type','application/json');

    }    
});


$app->run();
