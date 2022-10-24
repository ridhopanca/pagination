<?php 
    require_once './connection.php';

    if(function_exists($_GET['function'])){
        $_GET['function']();
    }

    function get_army()
    {
        global $connect;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 50;
        $lastSeek = isset($_POST['lastSeek']) ? $_POST['lastSeek'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $pagination = pagination($page, $pageSize);
        $condition = '';
        if($lastSeek != '') {
            if($type == 'next'){
                $condition  = $page > 1 ?  'where id > '.$lastSeek.'' : '';
            } else if ($type == 'prev'){
                $condition  = $page > 1 ?  'where id < '.$lastSeek.'' : '';
            }
        }
        $query = $connect->query("select * from army_detail ".$condition." order by id limit ".$pageSize."");
        $count = $connect->query("select count(*) as total from army_detail");
        while($row = mysqli_fetch_object($query))
        {
            $data[] = $row; 
        }
        $total = mysqli_fetch_assoc($count);
        $last = $pagination->to >= (int)$total['total'] ? $total['total'] : $pagination->to;
        $start = $pagination->from >= (int)$total['total'] ? $total['total'] : $pagination->from;
        if(empty($data)){
            $last = '~';
            $start = '~';
        }
        $response = [
            "status" => true,
            "message" => "success",
            "data" => $data,
            "total" => $total['total'],
            "prev" => $start,
            "next" => $last
        ];
        header('Content-type: application/json');
        echo json_encode($response);
    }

    function pagination(int $page = 1,int $pageSize = 50){
        $pagination = new stdClass;
        $start = $page <= 1 ? 1 : ((($page - 1) * $pageSize) + 1);
        $limit = $start + $pageSize - 1;
        $pagination->from = $start;
        $pagination->to = $limit;     
        return $pagination;
    }

    