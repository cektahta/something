<?php
require_once("Rest.inc.php");
require_once("DBConnection.php");
use DB\DBConnection;

class API extends REST  {

    public function __construct(){
        parent::__construct();

    }

    public function processApi()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $request = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
        $id = false;

        if (count($request) > 2 && $request[2][0] == ":")
        {
            $id = substr($request[2],1);
        }

        if (count($request) == 2 && $method == 'GET' && $request[1] == 'news')
        {
            $this->news();
        } else if (count($request) == 3 && $method == 'GET' && $request[1] == 'news' && is_numeric($id) )
        {
            $this->newsById($id);
        } else if (count($request) == 2 && $method == 'POST' && $request[1] == 'news')
        {
            $this->post();
        } else if (count($request) == 3 && $method == 'DELETE' && $request[1] == 'news' && is_numeric($id)) {
            $this->delete($id);
        } else {

        }

    }
    private function news()
    {
            $db_con = DBConnection::getInstance();

            $query = 'SELECT *
					FROM news
                    ';

            $sth = $db_con->prepare($query);
            $sth->execute();
            $row = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $array = [];
            foreach($row as $value) {
                $array[] = $value;

            }
            echo json_encode($array);
    }
    private function newsById ($id) {
        $db_con = DBConnection::getInstance();

        $query = 'SELECT *
					FROM news
				    WHERE
				    id = :id
                    ';

        $sth = $db_con->prepare($query);
        $sth->execute([':id' => $id]);

        $row = $sth->fetch(\PDO::FETCH_ASSOC);

        echo json_encode($row);
    }
    private function post()
    {
        $title = $_POST['title'];
        $text = $_POST['text'];
        $date = $_POST['date'];

        $db_con = DBConnection::getInstance();

        $queryInsert = "INSERT INTO news
							(title,text,created_at)
							VALUES (:title, :text, :created_at);";
        $stm = $db_con->prepare($queryInsert);
        $stm->execute([
            ':title' => $title,
            ':text' => $text,
            ':created_at' => $date,
        ]);
        $count = $stm->rowCount();
        if ($count == 1) {
            echo json_encode("success");
        } else {
            echo json_encode("not successfull");
        }


    }
    private function delete($id)
    {
        $db_con = DBConnection::getInstance();

        $query = 'DELETE
					FROM news
				    WHERE
				    id = :id
                    ';

        $sth = $db_con->prepare($query);
        $sth->execute([':id' => $id]);
        $count = $sth->rowCount();
        if ($count == 1) {
            echo json_encode("success");
        } else {
            echo json_encode("not successfull");
        }



    }










}



$api = new API;
$api->processApi();
?>