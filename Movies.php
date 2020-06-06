<?php
require_once('./IvoryV1/Operations.php');
require_once('GetDirectorsAuxiliarData.php');
require_once('GetActorsAuxiliarData.php');

$ivoryORM = new Operations;
$auxDirector = new GetDirectorsAuxiliarData;
$auxActor = new GetActorsAuxiliarData;

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        validations($data);

        $movieName = $data->name;
        $props["movie_name"] = $movieName;
        $movieRating = $data->rating;
        $props["movie_rating"] = $movieRating;

        $query = $ivoryORM->insert("movies", $props);
        $insertMovie = $ivoryORM->runQuery($query);

        if (!$insertMovie) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n "));
            exit;
        }

        $movieId = movieId($ivoryORM);
        $auxDirector->insertDirectorsInMovie($movieId, $ivoryORM, $data);
        $auxActor->insertActorsInMovie($movieId, $ivoryORM, $data);

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"New movie successfully created.Movie Id: $movieId"));
        break;

    case 'GET':
        $movieId = intval(filter_input(INPUT_GET,"id"));
        header("HTTP/1.1 200 OK");

        $fields[] = "*";
        $tables[] = "movies";

        $query = $ivoryORM->select($fields, $tables);
        if ($movieId && isset($movieId) && is_int($movieId)) {
            $where[] = [
                "propName" => "id",
                "fieldValue" => $movieId,
            ];
            $query = $ivoryORM->where($query, $where);
        }
        $selectMovies = $ivoryORM->runQuery($query);

        $moviesResponse = array();
        foreach ($selectMovies as $key => $value) {

            $moviesResponse[] = array_filter(
            $value, function($k) {
                return !is_int($k);
            }, ARRAY_FILTER_USE_KEY);
        }

        $moviesResponse["actors"] = $auxActor->actorsInMovie($movieId, $ivoryORM);

        $moviesResponse["directors"] = $auxDirector->directorsInMovie($movieId, $ivoryORM);

        echo json_encode($moviesResponse);
        break;

    case 'PUT':
        $movieId = filter_input(INPUT_GET,"id");

        if (!$movieId) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Actor id cannot be null"));
            exit;
        }

        $data = json_decode(file_get_contents("php://input"));

        validations($data);

        $movieName = $data->name;
        $props["movie_name"] = $movieName;
        $movieRating = $data->rating;
        $props["movie_rating"] = $movieRating;

        $whereUpdate[] = [
            "propName" => "id",
            "fieldValue" => $movieId,
        ];

        $query = $ivoryORM->update("movies", $props);
        $query = $ivoryORM->where($query,$whereUpdate);
        $updateActor = $ivoryORM->runQuery($query);

        if (!$updateActor) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n"));
            exit;
        }

        $auxDirector->insertDirectorsInMovie($movieId, $ivoryORM, $data);
        $auxActor->insertActorsInMovie($movieId, $ivoryORM, $data);

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"Movie successfully updated."));

        break;

    case 'DELETE':
        $movieId = intval(filter_input(INPUT_GET,"id"));
        if (!$movieId) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Movie id cannot be null"));
            exit;
        }
        if (isset($movieId) && !is_int($movieId)) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Incorrect id informed or incorrect id format id must be of type int $movieId"));
            exit;
        }

        $query = $ivoryORM->delete("movies");
        $whereCondition[] = [
            "propName" => "id",
            "fieldValue" => $movieId,
        ];
        $query = $ivoryORM->where($query, $whereCondition);
        $delete = $ivoryORM->runQuery($query);

        if (!$delete) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n " ));
            exit;
        }

        $auxDirector->removeDirectors($movieId, $ivoryORM);
        $auxActor->removeActors($movieId, $ivoryORM );

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"Movie successfully deleted."));

        break;
    
    default:
        header("HTTP/1.1 400 BAD REQUEST");
        echo json_encode(array("response"=>"Request type not expected"));
        break;
}

function validations($data)
{
    if (!$data) {
        header("HTTP/1.1 400 BAD REQUEST");
        echo json_encode(array("response"=>"Expected parameters but null given"));
        exit;
    }

    $errors =  array();
    if (!isset($data->name) || !is_string($data->name)) {
        array_push($errors,"Incorrect actor name informed name must be string");
    }

    if (!isset($data->rating) || !is_int(intval($data->rating))) {
        array_push($errors,"Incorrect movie rating informed name must be int");
    }

    if (count($errors) > 0) {
        header("HTTP/1.1 400 BAD REQUEST");
        echo json_encode(array("response"=>"Invalid or null fields",
        "errors"=>$errors));
        exit;
    }
}

function movieId($ivoryORM)
{
    $fields[] = "id";
    $tables[] = "movies";

    $query = $ivoryORM->select($fields, $tables);
    $query = $ivoryORM->order($query, "id");
    $query  = $ivoryORM->limit($query, 1);
    $returnLastInsertId = $ivoryORM->runQuery($query);

    foreach ($returnLastInsertId as $key => $value) {
        $movieId = $value["id"];
        return $movieId;
        break;
    }
    
}

?>