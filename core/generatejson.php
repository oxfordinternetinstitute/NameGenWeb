<?php

session_start();

$friend_list_array = $_SESSION['friend_list_array'];
$edge_list_array = $_SESSION['edge_list_array'];
$helper_array = array();

foreach ($friend_list_array as $key => $value) {
	
	$json_network['nodes'][]['name'] = $friend_list_array[$key]['name'];
	$helper_array[$friend_list_array[$key]['uid']] = $key;
	
}

foreach ($edge_list_array as $key => $value) {
	
	$uids = split(",", $key);
	
	$user1 = $helper_array[$uids[0]];
	$user2 = $helper_array[$uids[1]];	
	
	$json_network['links'][] = array('source' => $user1, 'target' => $user2);

}


echo json_encode($json_network);

?>