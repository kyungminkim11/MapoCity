<?
    include_once("./_common.php");

    $mb_6 = $member['mb_6'];
    $mb_7 = $member['mb_7'];


    $foodType = $filterConfig['foodType'];
    $sortType = $filterConfig['sortType'];
    $distanceType = $filterConfig['distanceType'];

    $sql_search = "";

    if($foodType){
        $sql_search .= " and food1 = '{$foodType}' "; 
    }

    if($sortType){
        if($sortType == "subs"){
            $sst = "desc";
        }else{
            $sst = "asc";
        }
        $order_by = " order by {$sortType} {$sst} "; 

    }else{

        $order_by = " order by distance asc "; 

    }

    if(!$distanceType){
        $distanceMeter = 0.3;
    }else{
        $distanceMeter = $distanceType;
    }

    $positions = array();

    //전체중 거리에서 가까운 순으로
    $sql = sql_query("SELECT * , ( 6371 * ACOS( COS( RADIANS( {$mb_6} ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lon ) - RADIANS( {$mb_7} ) ) + SIN( RADIANS( {$mb_6} ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM store_list having distance <= {$distanceMeter} {$sql_search} and status = '노출' {$order_by} ");

    //echo "SELECT * , ( 6371 * ACOS( COS( RADIANS( {$mb_6} ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lon ) - RADIANS( {$mb_7} ) ) + SIN( RADIANS( {$mb_6} ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM store_list having distance <= {$distanceMeter} {$sql_search} and status = '노출' {$order_by} ";

    for($i=0; $row = sql_fetch_array($sql); $i++){

        $positions['positions'][$i]['lat'] = (float)$row['lat'];
        $positions['positions'][$i]['lng'] = (float)$row['lon'];
    }

    //print_r($positions);
    echo json_encode($positions);
?>