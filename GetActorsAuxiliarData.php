<?php 
    class GetActorsAuxiliarData{

        public function actorsInMovie(int $movieId, Operations $ivoryORM)
        {
            $actorsInMovie = array();
            $actorsField[] = "actors.actor_name";
            $actorsField[] = "actors.id";

            $whereDirData[] = [
                "propName" => "actors_in_movie.movie_id",
                "fieldValue" => $movieId
            ];

            $whereDirData[] = [
                "propName" => "actors_in_movie.actor_id",
                "fieldValue" => "actors.id"
            ];

            $tableActorsInMovie[] = "actors_in_movie";
            $tableActorsInMovie[] = "actors";

            $query = $ivoryORM->select($actorsField, $tableActorsInMovie);
            $query = $ivoryORM->where($query, $whereDirData);
            
            $selectActors = $ivoryORM->runQuery($query);
        
            foreach ($selectActors as $key => $value) {

                $actorsInMovie[] = array_filter(
                $value, function($k) {
                    return !is_int($k);
                }, ARRAY_FILTER_USE_KEY);
            }

            return $actorsInMovie;
        }

        public function insertActorsInMovie(int $movieId, Operations $ivoryORM, $data)
        {
            if (isset($data->actors) && is_array($data->actors)) {
                $actorsData["movie_id"] = $movieId;

                foreach ($data->actors as $key => $value) {
                    $actorsData["actor_id"] = $value;

                    $query = $ivoryORM->insert("actors_in_movie", $actorsData);
                    $insertActorsInMovie = $ivoryORM->runQuery($query);
                }
            }
        }

        public function removeActors(int $movieId, Operations $ivoryORM)
        {  
            $where[] = [
                "propName" => "movie_id",
                "fieldValue" => $movieId,
            ];
            $query = $ivoryORM->delete("actors_in_movie");
            $query = $ivoryORM->where($query, $where);
            $ivoryORM->runQuery($query);
        }
    }
?>