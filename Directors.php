<?php
require_once('./IvoryV1/Operations.php');
$ivoryORM = new Operations;

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!$data) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Expected parameters but null given"));
            exit;
        }

        $errors =  array();
        if (!isset($data->name) || !is_string($data->name)) {
            array_push($errors,"Incorrect director name informed name must be string");
        }

        if (count($errors) > 0) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Invalid or null fields",
            "errors"=>$errors));
            exit;
        }

        $directorName = $data->name;
        $props["director_name"] = $directorName;
        $query = $ivoryORM->insert("directors", $props);
        $insertDirector = $ivoryORM->runQuery($query);

        if (!$insertDirector) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n "));
            exit;
        }

        $fields[] = "id";
        $tables[] = "directors";

        $query = $ivoryORM->select($fields, $tables);
        $query = $ivoryORM->order($query, "id");
        $query  = $ivoryORM->limit($query, 1);
        $returnLastInsertId = $ivoryORM->runQuery($query);

        foreach ($returnLastInsertId as $key => $value) {
            $directorId = $value["id"];
            break;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"New director successfully created.Director Id: $directorId"));
        break;

    case 'GET':
        $directorId = intval(filter_input(INPUT_GET,"id"));

        header("HTTP/1.1 200 OK");

        $fields[] = "*";
        $tables[] = "directors";

        $query = $ivoryORM->select($fields, $tables);
        if ($directorId && isset($directorId) && is_int($directorId)) {
            $where[] = [
                "propName" => "id",
                "fieldValue" => $directorId,
            ];
            $query = $ivoryORM->where($query, $where);
        }
        $selectDirectors = $ivoryORM->runQuery($query);

        $directorsResponse = array();
        foreach ($selectDirectors as $key => $value) {

            $directorsResponse[] = array_filter(
            $value, function($k) {
                return !is_int($k);
            }, ARRAY_FILTER_USE_KEY);
        }
        echo json_encode($directorsResponse);
        break;

    case 'PUT':
        $directorId = filter_input(INPUT_GET,"id");
        if (!$directorId) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Director id cannot be null"));
            exit;
        }
        $data = json_decode(file_get_contents("php://input"));
        if (!$data) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Expected parameters but null given"));
            exit;
        }

        $errors =  array();
        if (!isset($data->name) || !is_string($data->name)) {
            array_push($errors,"Incorrect director name informed name must be string");
        }

        if (count($errors) > 0) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Invalid or null fields",
            "errors"=>$errors));
            exit;
        }

        $directorName = $data->name;
        $props["director_name"] = $directorName;

        $whereUpdate[] = [
            "propName" => "id",
            "fieldValue" => $directorId,
        ];

        $query = $ivoryORM->update("directors", $props);
        $query = $ivoryORM->where($query,$whereUpdate);
        $updatedirector = $ivoryORM->runQuery($query);

        if (!$updatedirector) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n"));
            exit;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"Director successfully updated."));

        break;

    case 'DELETE':
        $directorId = intval(filter_input(INPUT_GET,"id"));
        if (!$directorId) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"director id cannot be null"));
            exit;
        }
        if (isset($directorId) && !is_int($directorId)) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Incorrect id informed or incorrect id format id must be of type int $directorId"));
            exit;
        }

        $query = $ivoryORM->delete("directors");
        $whereCondition[] = [
            "propName" => "id",
            "fieldValue" => $directorId,
        ];
        $query = $ivoryORM->where($query, $whereCondition);
        $delete = $ivoryORM->runQuery($query);

        if (!$delete) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n " ));
            exit;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"Director successfully deleted."));

        break;
    
    default:
        header("HTTP/1.1 400 BAD REQUEST");
        echo json_encode(array("response"=>"Request type not expected"));
        break;
}

?>