<?php
class mysql_helpers{
    private $database;
    function __construct(){
        try {
            $this->database = new PDO("mysql:host=". Host .";dbname=". Dbname .";charset=UTF8", Username, Password);
        }catch (PDOException $err){
            echo "Connect Error" . $err->getMessage();
            die();
        }
    }
    public function read($sql, ...$params){
        if(other_helpers::arrayIsNull($params)){
            return false;
        }else{
            $db = $this->database;
            $query = $db->prepare($sql);
            $query->execute($params);
            $queryCount = $query->rowCount();
            $datas = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($queryCount > 0){
                return $datas;
            }else{
                return false;
            }
        }
    }
    public function create($table, $columns, ...$datas){
        if (other_helpers::arrayIsNull($datas)){
            return false;
        }else{
            $db = $this->database;
            $values = str_repeat("?,", substr_count($columns, ",")) . " ?";
            $query = $db->prepare("insert into ". $table ." (". other_helpers::security($columns) .") values (". $values .")");
            $query->execute($datas);
            $success = $query->rowCount();
            if ($success > 0){
                return $db->lastInsertId();
            }else{
                return false;
            }
        }
    }
    public function delete($table, $id, $column = "id"){
        if (!empty($id)){
            $db = $this->database;
            $query = $db->prepare("delete from ". $table ." where ". $column ." = ?");
            $query->execute([$id]);
            $success = $query->rowCount();
            if ($success > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function update($table, $columns, $id, ...$datas){
        if ((empty($id)) or (other_helpers::arrayIsNull($datas))){
            return false;
        }else{
            $db = $this->database;
            $columns = str_replace(",", "=?,", $columns). "=?";
            $query = $db->prepare("update ". $table ." set ". $columns ." where id = ? limit 1");
            $datas[] = $id;
            $query->execute($datas);
            $success = $query->rowCount();
            if ($success > 0){
                return true;
            }else{
                return false;
            }
        }
    }
}
?>