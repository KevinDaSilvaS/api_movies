<?php 
    class GetDirectorsAuxiliarData{

        public function directorsInMovie(int $movieId, Operations $ivoryORM)
        {
            $directorsInMovie = array();
            $directorsField[] = "directors.director_name";
            $directorsField[] = "directors.id";

            $whereDirData[] = [
                "propName" => "directors_in_movie.movie_id",
                "fieldValue" => $movieId
            ];

            $whereDirData[] = [
                "propName" => "directors_in_movie.directors_id",
                "fieldValue" => "directors.id"
            ];

            $tableDirectorsInMovie[] = "directors_in_movie";
            $tableDirectorsInMovie[] = "directors";

            $query = $ivoryORM->select($directorsField, $tableDirectorsInMovie);
            $query = $ivoryORM->where($query, $whereDirData);
            
            $selectDirectors = $ivoryORM->runQuery($query);
        
            foreach ($selectDirectors as $key => $value) {

                $directorsInMovie[] = array_filter(
                $value, function($k) {
                    return !is_int($k);
                }, ARRAY_FILTER_USE_KEY);
            }

            return $directorsInMovie;
        }

        public function insertDirectorsInMovie(int $movieId, Operations $ivoryORM, $data)
        {
            if (isset($data->directors) && is_array($data->directors)) {
                $directorsData["movie_id"] = $movieId;
                
                foreach ($data->directors as $key => $value) {
                    $directorsData["directors_id"] = $value;

                    $query = $ivoryORM->insert("directors_in_movie", $directorsData);
                    $insertDirectorsInMovie = $ivoryORM->runQuery($query);
                }
            }

        }

        public function removeDirectors(int $movieId, Operations $ivoryORM)
        {  
            $where[] = [
                "propName" => "movie_id",
                "fieldValue" => $movieId,
            ];
            $query = $ivoryORM->delete("directors_in_movie");
            $query = $ivoryORM->where($query, $where);
            $ivoryORM->runQuery($query);
        }
    }

    
?>