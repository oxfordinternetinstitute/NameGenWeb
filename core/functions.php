<?PHP 

// NameGen function declarations

/**
* Fix for Facebook native API calls causing "resource limit exceeded"
*
* @access public
* @param string $url	URL to request using CURL library
* @return array $response
*
*/
function makeCall($url) {
    // Make a call by curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
    $response = curl_exec($ch); 
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    curl_close($ch); 
    $response = json_decode($response);
    return $response;
}

/**
* Removes invalid XML
*
* @access public
* @param string $value
* @return string
*
*/
function stripInvalidXml($value)
{
    $ret = "";
    $current;
    if (empty($value)) 
    {
        return $ret;
    }
 
    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($value{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
    return $ret;
}


function createGraphFile($node_array, $edge_array, $attribute_array, $format, $output_file, $include_ego = FALSE, $anonymise = FALSE) {
	
/**
* Takes alter and edge data, and outputs a file of type $format with filename $output_file
*
* @param array 	$node_array 		Array of node and node attribute data.
* @param array 	$edge_array 		Array of edges linked by id to node array.
* @param array 	$attribute_array 	Array of node attribute names.
* @param string	$format				One of graphml, ucinet, json, guess.
* @param string $output_file		Filename (without extension) to be output.
* @param bool	$include_ego		Whether to include ego in resulting file.
* @param bool	$anonymise			Whether to anonymise names.
*
* @return string $path				The path to the file created.
*
* TODO:
* 	 	- Update GraphML generation to use PHPs DOMDOCUMENT
*
*/	
	
$output = ""; // File content variable
$extension = ""; // File extension
$output_directory = "../output/"; // Set global output directory


// insert ego if requested

if($include_ego == TRUE) {
	
	$ego_name = "Ego";
	$ego_uid = "0000000";
	
	$node_array['ego']['name'] = $ego_name;
	$node_array['ego']['uid'] = $ego_uid;
	
	foreach ($node_array as $key => $value) {
		$edge_array[$ego_uid.",".$node_array[$key]['uid']] = 1;
		
	}		
	
}

// Anonymise if requested

function getAnonID() {
	
	$id = uniqid('', FALSE);
	return $id;
}

if($anonymise == TRUE) {
	
	$temp_array = array(); //stuid design decision bites me in the ass.
	$i = 0;
	foreach ($edge_array as $edgekey => $edgevalue) {
		$uid = explode(",", $edgekey);
		$temp_array[$i]['source'] = $uid[0];
		$temp_array[$i]['target'] = $uid[1];
		$i++;		
	}
	
	$temp_node_array = array();
	$temp_edge_array = $temp_array;
	
	foreach ($node_array as $key => $value) {
		
		$anon_id = getAnonID();
		$temp_node_array[$key]['name'] = $anon_id;
		$temp_node_array[$key]['uid'] = $anon_id;
		
		foreach ($temp_edge_array as $edgekey => $edgevalue) {
			
			$temp_edge_array[$edgekey]['source'] = str_replace($node_array[$key]['uid'], $anon_id, $temp_edge_array[$edgekey]['source']);	 
			$temp_edge_array[$edgekey]['target'] = str_replace($node_array[$key]['uid'], $anon_id, $temp_edge_array[$edgekey]['target']);
		}
		
	}
	
	unset($edge_array);
	$edge_array = array();
	foreach ($temp_edge_array as $key => $value) {
		$new_key = implode(",", $temp_edge_array[$key]);
		$edge_array[$new_key] = 1;
	}
	
	$node_array = $temp_node_array;
	
}

// Create an array with key value pairs reversed for fast and easy lookup. Also create ['safe_name'] for less robust file formats
$helper_array = array();

foreach ($node_array as $key => $value) {

	$helper_array[$node_array[$key]['uid']]['name'] = $node_array[$key]['name'];
	$safe_name = str_replace(' ', '_', $node_array[$key]['name']);
	$safe_name.= "_".$node_array[$key]['uid'];
	$helper_array[$node_array[$key]['uid']]['safe_name'] = $safe_name;

}

// Bypass the Manuel issue (User has died. User doesnt show in in friend list, but shows up in mutual friends.)
// SOLUTION 1: We check all UIDs in the edge_list are present somewhere in the node_list. if not, we unset() them.
// SOLUTION 2: We create a new node with no attributes (the gephi "autocreate" option) which preserves the connections.

$edge_list_temp = array();

foreach ($edge_array as $key => $value) {

	$uid = explode(",", $key);
	
	if ((array_key_exists($uid[0], $helper_array)) && (array_key_exists($uid[1], $helper_array))) {
		$edge_list_temp[$key] = 1; 
	}
	unset($edge_array);
	$edge_array = array();
	$edge_array = $edge_list_temp;	
}
	
// ********************************************************************************************
// SET FILE EXTENSION
// ********************************************************************************************

	switch($format) {
		
		case 'graphml':
			$extension = ".graphml";
			$output.= '<?xml version="1.0" encoding="UTF-8"?>
			<graphml xmlns="http://graphml.graphdrawing.org/xmlns"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://graphml.graphdrawing.org/xmlns
			http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd">';
			break;

		case 'ucinet':
			$extension = ".dl";
			$friend_count = count($node_array);
			$output.= "dl n = $friend_count format = edgelist1\nlabels:\n";			
			break;

		case 'json':
			$extension = ".json";
			$helper_array = array();
			break;
		
		case 'guess':
			$extension = ".gdf";
			$output.= "nodedef>name";
			foreach ($attribute_array as $key => $value) {

				$type = (($key == "mutual_friend_count") || ($key == "friend_count") || ($key == "likes_count")) ? "int" : "string";
				$attributes[$key] = $type;
				$output.=",".$key." ".strtoupper($type);

			}
			
			$output.="\n";
						
			break;
		
		default:
			return die("Requested format not available.");
		
	}

	// Path to file
	$path = $output_directory.$output_file.$extension;
	
	// Check we can write where we want to write
	if (is_writable($output_directory)) {
		$file = fopen($path, "a");
	} else {
		return die("File is not writable.");
	}
	

// ********************************************************************************************
// HANDLE ATTRIBUTE DATA
// ********************************************************************************************
	
	$attributes ="";

	switch($format) {
		
		case 'graphml':
			foreach ($attribute_array as $key => $value) {

				$type = (($value == "mutual_friend_count") || ($value == "friend_count") || ($value == "likes_count")) ? "int" : "string";
				$attributes.= "\n".'<key id="'.$value.'" for="node" attr.name="'.$value.'" attr.type="'.$type.'">'."\n\t".'<default></default>'."\n".'</key>';

			}
			$attributes.= "\n".'<key id="Label" for="node" attr.name="Label" attr.type="string">'."\n\t".'<default></default>'."\n".'</key>';
			$attributes.='<graph id="G" edgedefault="undirected">'."\n";			
			break;

		case 'ucinet':
		
			break;

		case 'json':

			break;
		
		case 'guess':
		
			break;

	}

	$output.=$attributes;
	
// ********************************************************************************************
// HANDLE NODE DATA
// ********************************************************************************************
	
	$nodes = "";
	
	switch($format) {
		
		case 'graphml':
		
			$attribute_helper_array = array();
			foreach ($attribute_array as $key => $value) {
				$attribute_helper_array[$value] = $key;
			}
		
			foreach ($node_array as $key => $value) {	

				$nodes.= "\n".'<node id="'.$helper_array[$node_array[$key]['uid']]['safe_name'].'">';
				$nodes.="\n\t".'<data key="Label">'.$helper_array[$node_array[$key]['uid']]['name'].'</data>';				
				foreach ($node_array[$key] as $attribute => $attr_value) {
					
					if ($attribute =="hometown_location") {
						$attr_value = $node_array[$key]['hometown_location']['name'];
					}
					
					if (isset($attr_value)) {
						$attr_value = htmlspecialchars($attr_value);
					    $attr_value = "<![CDATA[".$attr_value."]]>";							
						$helper_key = $attribute_helper_array[$attribute];
						$nodes.="\n\t".'<data key="'.$attribute.'">'.$attr_value.'</data>';												
					}

				

				}	
				$nodes.= "\n".'</node>';	

			}
			
			break;

		case 'ucinet':
			foreach ($node_array as $key => $value) {	

				$nodes.= $helper_array[$node_array[$key]['uid']]['safe_name']."\n";	

			}
			break;

		case 'json':
			foreach ($node_array as $key => $value) {

				$json_network['nodes'][]['name'] = $node_array[$key]['name'];
				$helper_array[$node_array[$key]['uid']] = $key;

			}
			break;
		
		case 'guess':
			foreach ($node_array as $key => $value) {	

				$nodes.= $node_array[$key]['uid']."\n";	

			}	
			break;

	}
	
	$output.=$nodes;

// ********************************************************************************************
// HANDLE EDGE DATA
// ********************************************************************************************
	
	$i=0;
	$edges="";

	switch($format) {
		
		case 'graphml':
		
			foreach ($edge_array as $key => $value) {

				$uids = explode(",", $key);	 
				$edges.="\n".'<edge id="'.$i.'" source="'.$helper_array[$uids[0]]['safe_name'].'" target="'.$helper_array[$uids[1]]['safe_name'].'"></edge>';
				$i++;

			}
		
			break;

		case 'ucinet':
			$output.= "labels embedded\ndata:\n";		
			foreach ($edge_array as $key => $value) {
				$uids = explode(",", $key);		 
				$edges.=$helper_array[$uids[0]]['safe_name']." ".$helper_array[$uids[1]]['safe_name']."\n";
			}		
			break;

		case 'json':
			foreach ($edge_array as $key => $value) {

				$uids = explode(",", $key);

				$user1 = $helper_array[$uids[0]];
				$user2 = $helper_array[$uids[1]];	

				$json_network['links'][] = array('source' => $user1, 'target' => $user2);

			}
			break;
		
		case 'guess':
		$output.= "edgedef>node1,node2\n";		
			foreach ($edge_array as $key => $value) {	 
				$edges.=$key."\n";
			}
			break;

	}
	
	$output.=$edges;

// ********************************************************************************************
// HANDLE FILE WRITE AND RETURN
// ********************************************************************************************

	switch($format) {
	
		case 'graphml':
			$output.="\n".'</graph></graphml>';
			break;

		case 'ucinet':
	
			break;

		case 'json':
			$output = json_encode($json_network);
			break;
	
		case 'guess':
	
			break;

	}	

	$output =  stripInvalidXml($output); // Fixes illegal XML character bug
	fputs($file, $output);
	
	return $path;
	
}



function downloadPrompt($path) {
	
/**
* Prompts the user to download a file in the browser.
* Works even with IE6.
*
* @param string $path                   The file path to the file to be downloaded
* @param string $browserFilename        The name sent to the browser
* @param string $mimeType               The mime type like 'image/png'
*
* @return void
*/	

	if (!file_exists($path) || !is_readable($path)) {

		return null;
	}

$browserFilename = basename($path);
$mimeType = exec("file -bi " . escapeshellarg($path));

	header("Content-Type: " . $mimeType);
	date_default_timezone_set('Europe/London');
	header("Content-Disposition: attachment; filename=\"$browserFilename\"");
	header('Expires: ' . date('D, d M Y H:i:s', time() - 3600) . ' GMT');
	header("Content-Length: " . filesize($path));
	// If you wish you can add some code here to track or log the download

	// Special headers for IE 6
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	$fp = fopen($path, "r");
	fpassthru($fp);
}


?>