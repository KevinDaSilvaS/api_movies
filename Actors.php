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
            array_push($errors,"Incorrect actor name informed name must be string");
        }

        if (count($errors) > 0) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Invalid or null fields",
            "errors"=>$errors));
            exit;
        }

        $actorName = $data->name;
        $props["actor_name"] = $actorName;
        $query = $ivoryORM->insert("actors", $props);
        $insertActor = $ivoryORM->runQuery($query);

        if (!$insertActor) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n "));
            exit;
        }

        $fields[] = "id";
        $tables[] = "actors";

        $query = $ivoryORM->select($fields, $tables);
        $query = $ivoryORM->order($query, "id");
        $query  = $ivoryORM->limit($query, 1);
        $returnLastInsertId = $ivoryORM->runQuery($query);

        foreach ($returnLastInsertId as $key => $value) {
            $actorId = $value["id"];
            break;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"New actor successfully created.Actor Id: $actorId"));
        break;

    case 'GET':
        $actorId = intval(filter_input(INPUT_GET,"id"));
        header("HTTP/1.1 200 OK");

        $fields[] = "*";
        $tables[] = "actors";

        $query = $ivoryORM->select($fields, $tables);
        if ($actorId && isset($actorId) && is_int($actorId)) {
            $where[] = [
                "propName" => "id",
                "fieldValue" => $actorId,
            ];
            $query = $ivoryORM->where($query, $where);
        }
        $selectActors = $ivoryORM->runQuery($query);

        $actorsResponse = array();
        foreach ($selectActors as $key => $value) {

            $actorsResponse[] = array_filter(
            $value, function($k) {
                return !is_int($k);
            }, ARRAY_FILTER_USE_KEY);
        }
        echo json_encode($actorsResponse);
        break;

    case 'PUT':
        $actorId = filter_input(INPUT_GET,"id");
        if (!$actorId) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Actor id cannot be null"));
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
            array_push($errors,"Incorrect actor name informed name must be string");
        }

        if (count($errors) > 0) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Invalid or null fields",
            "errors"=>$errors));
            exit;
        }

        $actorName = $data->name;
        $props["actor_name"] = $actorName;

        $whereUpdate[] = [
            "propName" => "id",
            "fieldValue" => $actorId,
        ];

        $query = $ivoryORM->update("actors", $props);
        $query = $ivoryORM->where($query,$whereUpdate);
        $updateActor = $ivoryORM->runQuery($query);

        if (!$updateActor) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n"));
            exit;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"Actor successfully updated."));

        break;

    case 'DELETE':
        $actorId = intval(filter_input(INPUT_GET,"id"));
        if (!$actorId) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Actor id cannot be null"));
            exit;
        }
        if (isset($actorId) && !is_int($actorId)) {
            header("HTTP/1.1 400 BAD REQUEST");
            echo json_encode(array("response"=>"Incorrect id informed or incorrect id format id must be of type int $actorId"));
            exit;
        }

        $query = $ivoryORM->delete("actors");
        $whereCondition[] = [
            "propName" => "id",
            "fieldValue" => $actorId,
        ];
        $query = $ivoryORM->where($query, $whereCondition);
        $delete = $ivoryORM->runQuery($query);

        if (!$delete) {
            header("HTTP/1.1 500 INTERNAL SERVER ERROR");
            echo json_encode(array("response"=>"Unexpected exception happened.\n " ));
            exit;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response"=>"Actor successfully deleted."));

        break;
    
    default:
        header("HTTP/1.1 400 BAD REQUEST");
        echo json_encode(array("response"=>"Request type not expected"));
        break;
}

?>