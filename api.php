<?php
//connection to DB
$conn = null;

if($_SERVER['REQUEST_METHOD']=='GET'){
  if (isset($_GET["function"])) {
    $conn = connectToDB();
    $requiredParam = [];
    $response = "";
    switch ($_GET["function"]) {
      //http://localhost/Walkhome/api.php?function=addWalk&phone=6132030017&team=1&time=2013-08-05%2018:19:03&status=1&up=Leggett%20Hall&drop=ARC
      case 'addWalk':
        $requiredParam = ["team","request_time","status","pick_up_location","drop_off_location","phone_number"];
        if(checkParam($requiredParam)){
          $response = addWalk($_GET["team"],$_GET["request_time"],$_GET["status"],$_GET["pick_up_location"],$_GET["drop_off_location"],$_GET["phone_number"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=createUser&phone=6132030017&deviceToken=aea604e577c48ecc7cab907422d9f7e568081c953f88c82f1b6b7f5b9f3061f5
      case 'createUser':
        if (isset($_GET["phone"])) {
          if (isset($_GET["device_token"])) {
            $response = createUser($_GET["phone"],$_GET["device_token"]);
          }else{
            $response = createUser($_GET["phone"]);
          }
        }else{
          echo json_encode(array(
              'status' => 500,
              'error' => "Error: missing parameters"
          ));
        }
        break;
      //http://localhost/Walkhome/api.php?function=getActiveWalks&lastUpdate=2016-08-05%2018:19:03
      case 'getActiveWalks':
        $requiredParam = ["lastUpdate"];
        if(checkParam($requiredParam)){
          $response = getActiveWalks($_GET["lastUpdate"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=getWalkByID&id=10
      case 'getWalkByID':
        $requiredParam = ["id"];
        if(checkParam($requiredParam)){
          $response = getWalkByID($_GET["id"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=getWalkByUserPhoneNumber&phone_number=6132030017
      case 'getWalkByUserPhoneNumber':
        $requiredParam = ["phone_number"];
        if(checkParam($requiredParam)){
          $response = getWalkByUserPhoneNumber($_GET["phone_number"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=getUserByID&id=0
      case 'getUserByID':
        $requiredParam = ["id"];
        if(checkParam($requiredParam)){
          $response = getUserByID($_GET["id"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=getUserByPhoneNumber&phone_number=6132030017
      case 'getUserByPhoneNumber':
        $requiredParam = ["phone_number"];
        if(checkParam($requiredParam)){
          $response = getUserByPhoneNumber($_GET["phone_number"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=updateWalk&id=12&phone=6132030017&team=1&time=2013-08-05%2018:19:03&status=3&up=Leggett%20Hall&drop=ARC
      case 'updateWalk':
        $requiredParam = ["id","team","request_time","status","pick_up_location","drop_off_location","phone_number"];
        if(checkParam($requiredParam)){
          $response = updateWalk($_GET["id"],$_GET["team"],$_GET["request_time"],$_GET["status"],$_GET["pick_up_location"],$_GET["drop_off_location"],$_GET["phone_number"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=updateWalk&id=12&phone=6132030017&team=1&time=2013-08-05%2018:19:03&status=3&up=Leggett%20Hall&drop=ARC
      case 'cancelWalk':
        $requiredParam = ["id"];
        if(checkParam($requiredParam)){
          $response = cancelWalk($_GET["id"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=updateWalk&id=12&phone=6132030017&team=1&time=2013-08-05%2018:19:03&status=3&up=Leggett%20Hall&drop=ARC
      case 'deleteWalk':
        $requiredParam = ["id"];
        if(checkParam($requiredParam)){
          $response = deleteWalk($_GET["id"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=feedback&message=It is a good app
      case 'feedback':
        $requiredParam = ["message","phone_number","time"];
        if(checkParam($requiredParam)){
          $response = feedback($_GET["message"],$_GET["phone_number"],$_GET["time"]);
        }
        break;
      //http://localhost/Walkhome/api.php?function=feedback&message=It is a good app
      case 'isOpen':
        $response = isOpen();
        break;
      //http://localhost/Walkhome/api.php?function=sendPush&device_token=aea604e577c48ecc7cab907422d9f7e568081c953f88c82f1b6b7f5b9f3061f5&message=hello
      case 'sendPush':
        $requiredParam = ["device_token","message"];
        if(checkParam($requiredParam)){
          $response = sendPush($_GET["device_token"],$_GET["message"]);
        }
        break;
      default:
        break;
    }
    echo json_encode($response);
  }else{
    echo '<pre>Function: addWalk<br>
Parameters:<br>
                team: name of the walk team ex: "W1"<br>
                request_time: when they want to be picked up ex: "2013-08-05%2018:19:03"<br>
                status: what stage is the walk at ex: 1<br>
                pick_up_location: ex: "The Spot"<br>
                drop_off_location: ex: "123 Princess"<br>
                phone_number: ex: "6132030017"<br>
Function: createUser<br>
Function: getActiveWalks<br>
        Parameters:<br>
                lastUpdate: ex: "2013-08-05%2018:19:03"<br>
Function: getWalkByID<br>
        Parameters:<br>
                id: walk ID ex: 12<br>
Function: getWalkByUserPhoneNumber<br>
        Parameters:<br>
                phone_number: ex: "6132030017"<br>
Function: getUserByID<br>
        Parameters:<br>
                id: user ID ex: 4 <br>
Function: getUserByPhoneNumber<br>
        Parameters:<br>
                phone_number: ex: "6132030017"<br>
Function: updateWalk<br>
        Parameters:<br>
                id: walk ID ex: 12<br>
                team: name of the walk team ex: "W1"<br>
                request_time: when they want to be picked up ex: "2013-08-05%2018:19:03"<br>
                status: what stage is the walk at ex: 1<br>
                pick_up_location: ex: "The Spot"<br>
                drop_off_location: ex: "123 Princess"<br>
                phone_number: ex: "6132030017"<br>
Function: cancelWalk<br>
        Parameters:<br>
                id: walk ID ex: 12<br>
Function: deleteWalk<br>
        Parameters:<br>
                id: walk ID ex: 12<br>
Function: feedback<br>
        Parameters:<br>
                message: the message of the feedback ex: "Thats a nice app"<br>
                phone_number: optional to connect to last walk ex: "6132030017"<br>
                time: time of message ex: "2013-08-05%2018:19:03"<br>
Function: isOpen<br>
Function: sendPush<br>
        Parameters:<br>
                device_token: the devices id for sending iOS push notifications ex: "aea604e577c48ecc7cab907422d9f7e568081c953f88c82f1b6b7f5b9f3061f5"<br>
                message: ex: "Status: walkers out"<br></pre>';
  }
}

function connectToDB(){
  $SQLservername = "localhost";
  $SQLusername = "readwrite";
  $SQLpassword = "tf3a64af518ex";
  $SQLdbname = "walkhome";

  // Create connection
  $conn = mysqli_connect($SQLservername, $SQLusername, $SQLpassword, $SQLdbname);
  // Check connection
  if (mysqli_connect_errno()) {
    $error =  "Failed to connect to MySQL: " . mysqli_connect_error();
    $json = array(
        'status' => 500,
        'error' => $error
    );
    $jsonstring = json_encode($json);
    echo $jsonstring;
    return null;
  }else{
    return $conn;
  }
}

function checkParam($params){
  foreach ($params as $param) {
    if (!isset($_GET[$param])) {
      $error =  "Error: missing ".$param." parameter";
      $json = array(
          'status' => 500,
          'error' => $error
      );
      $jsonstring = json_encode($json);
      echo $jsonstring;
      return False;
    }
  }
  return True;
}

function isOpen(){
  //find out if open
  $json = array(
    'status' => 200
  );
  return $json;
}

function addWalk($team,$time,$status,$up,$drop,$phone){
  global $conn;
  $userRet = createUser($phone);
  $realUser = ($userRet["status"] == 201);
  //add walk
  $sql = "INSERT INTO walk (user_id, team, pick_up_location, drop_off_location, status, request_time, active) VALUES ('".$userRet["user"]["id"]."','".$team."','".$up."','".$drop."',1,'".$time."',1)";
  $conn->query($sql);
  $id = $conn->insert_id;
  if ($realUser) {
    sendPush($userRet["user"]["id"],"Your walk request is recived.");
  }
  $json = array(
    'status' => 200,
    'id' => $id
  );
  return $json;
}

function createUser($phone, $deviceToken = ""){
  global $conn;
  $noToken = ($deviceToken == "");
  $deviceTokenServer = "";
  $phoneServer = "";
  $idServer = 0;
  $status = 500;

  $sql = "SELECT id, device_token FROM user WHERE phone_number = '".$phone."'";
  if ($result=mysqli_query($conn,$sql) AND mysqli_num_rows($result)!=0){
    while ($row=mysqli_fetch_row($result)){
      $idServer = $row[0];
      $deviceTokenServer = $row[1];
      $phoneServer = $phone;
    }
    if ($deviceToken != "") {
      $sql = "UPDATE user SET device_token = '".$deviceToken."' WHERE id = '".$idServer."'";
      $conn->query($sql);
      $deviceTokenServer = $deviceToken;
    }
    // Free result set
    mysqli_free_result($result);
    $status = 201;
  }else{
    //no user
    if(!$noToken){
      //create user with no token
      $sql = "INSERT INTO user (phone_number) VALUES ('".$phone."')";
      $conn->query($sql);
      $idServer = $conn->insert_id;
      $phoneServer = $phone;
      $status = 202;
    }else{
      //create full user
      $sql = "INSERT INTO user (phone_number, device_token) VALUES ('".$phone."','".$deviceToken."')";
      $conn->query($sql);
      $idServer = $conn->insert_id;
      $phoneServer = $phone;
      $deviceTokenServer = $deviceToken;
      $status = 200;
    }
  }
  $json = array(
    'status' => $status,
    'user' => array(
      'id' => $idServer,
      'phone' => $phoneServer,
      'deviceToken' => $deviceTokenServer
    )
  );
  return $json;
}

function getActiveWalks($lastUpdate){
  global $conn;
  $walks=array();
  $removedWalks=array();
  $status = 500;
  $sql = "SELECT id, user_id, team, pick_up_location, drop_off_location, status, request_time, active FROM walk WHERE edit_time > '".$lastUpdate."'";
  if ($result=mysqli_query($conn,$sql) AND mysqli_num_rows($result)!=0){
    while ($row=mysqli_fetch_row($result)){
      if ($row[7] == 1) {
        $user = getUserByID($row[1]);
        $phone_number = "None";
        if ($user['status'] == 200) {
          $phone_number = getUserByID($row[1])["user"]["phone_number"];
        }
        array_push($walks,array(
          'id' => $row[0],
          'user_id' => $row[1],
          'phone_number' => $phone_number,
          'team' => $row[2],
          'pick_up_location' => $row[3],
          'drop_off_location' => $row[4],
          'status' => $row[5],
          'request_time' => $row[6],
          'active' => $row[7]
        ));
      }else{
        array_push($removedWalks,array(
          'id' => $row[0]
        ));
      }
    }
    // Free result set
    mysqli_free_result($result);
    $status = 200;
  }else{
    $status = 201;
    //no walks
  }
  $json = array(
    'status' => $status,
    'walks' => $walks,
    'removedWalks' => $removedWalks
  );
  return $json;
}

function getWalkByID($id){
  global $conn;
  $walk = array();
  $sql = "SELECT id, user_id, team, pick_up_location, drop_off_location, status, request_time, active FROM walk WHERE id = '".$id."'";
  if ($result=mysqli_query($conn,$sql) AND mysqli_num_rows($result)!=0){
    while ($row=mysqli_fetch_row($result)){
      $walk = array(
        'id' => $row[0],
        'user_id' => $row[1],
        'team' => $row[2],
        'pick_up_location' => $row[3],
        'drop_off_location' => $row[4],
        'status' => $row[5],
        'request_time' => $row[6],
        'active' => $row[7]
      );
    }
    // Free result set
    mysqli_free_result($result);
    $status = 200;
  }else{
    //no walk
    $status = 404;
  }
  $json = array(
    'status' => $status,
    'walk' => $walk
  );
  return $json;
}

function getWalkByUserPhoneNumber($phone_number){
  global $conn;
  $userRet = createUser($phone_number);
  $walk = array();
  if ($userRet["status"] == 201){
    $sql = "SELECT id, user_id, team, pick_up_location, drop_off_location, status, request_time, active".
            " FROM walk s1".
            " WHERE request_time = (SELECT MAX(request_time) FROM walk s2 WHERE s1.user_id = s2.user_id AND user_id = '".$userRet["user"]["id"]."')".
            " AND user_id = '".$userRet["user"]["id"]."'".
            " GROUP BY user_id;";
    if ($result=mysqli_query($conn,$sql) AND mysqli_num_rows($result)!=0){
      while ($row=mysqli_fetch_row($result)){
        $walk = array(
          'id' => $row[0],
          'user_id' => $row[1],
          'team' => $row[2],
          'pick_up_location' => $row[3],
          'drop_off_location' => $row[4],
          'status' => $row[5],
          'request_time' => $row[6],
          'active' => $row[7]
        );
      }
      // Free result set
      mysqli_free_result($result);
      $status = 200;
    }else{
      //no walk
      $status = 404;
    }
  }else{
    //no user
    $status = 405;
  }
  
  $json = array(
    'status' => $status,
    'walk' => $walk
  );
  return $json;
}

function getUserByID($id){
  global $conn;
  $user = array();
  $sql = "SELECT id, phone_number, device_token FROM user WHERE id = '".$id."'";
  if ($result=mysqli_query($conn,$sql) AND mysqli_num_rows($result)!=0){
    while ($row=mysqli_fetch_row($result)){
      $user = array(
        'id' => $row[0],
        'phone_number' => $row[1],
        'device_token' => $row[2]
      );
    }
    // Free result set
    mysqli_free_result($result);
    $status = 200;
  }else{
    //no user
    $status = 404;
  }
  $json = array(
    'status' => $status,
    'user' => $user
  );
  return $json;
}

function getUserByPhoneNumber($phone_number){
  global $conn;
  $user = array();
  $sql = "SELECT id, phone_number, device_token FROM user WHERE phone_number = '".$phone_number."'";
  if ($result=mysqli_query($conn,$sql) AND mysqli_num_rows($result)!=0){
    while ($row=mysqli_fetch_row($result)){
      $user = array(
        'id' => $row[0],
        'phone_number' => $row[1],
        'device_token' => $row[2]
      );
    }
    // Free result set
    mysqli_free_result($result);
    $status = 200;
  }else{
    //no user
    $status = 404;
  }
  $json = array(
    'status' => $status,
    'user' => $user
  );
  return $json;
}

function updateWalk($id,$team,$time,$status,$up,$drop,$phone){
  global $conn;
  $statusText = array(
    "1" => "Recived",
    "2" => "Walkers Out",
    "3" => "Walking",
    "4" => "Walk Complete",
    "5" => "Walkers In"
  );
  $user = createUser($phone);
  $func_status = 500;
  $walkResponce = getWalkByID($id);
  $walk = $walkResponce['walk'];
  if ($walkResponce["status"] == 200) {
    //walk exists
    //we have device token so we send a push notification
    if ($user["status"] == 201) {
      if($walk["status"] != $status && !($walk["status"] == 4 && $status == 5)){
        $func_status = sendPush($user["user"]["deviceToken"],"Walk status: ".$statusText[$status])['status'];
      }else{
        $func_status = 201;
      }
    }else{
      $func_status = 200;
    }
    $walk['user_id'] = $user["user"]["id"];
    $walk['team'] = $team;
    $walk['pick_up_location'] = $up;
    $walk['drop_off_location'] = $drop;
    $walk['status'] = $status;
    $walk['request_time']  =$time;
    if ($status == 5) {
      $walk['active'] = 0;
    }else{
      $walk['active'] = 1;
    }

    $sql = "UPDATE walk SET user_id = '".$walk['user_id']."', team = '".$walk['team']."', pick_up_location = '".$walk['pick_up_location']."', drop_off_location = '".$walk['drop_off_location']."', status = '".$walk['status']."', request_time = '".$walk['request_time']."', active = '".$walk['active']."' WHERE id = '".$id."'";
    $conn->query($sql);
  }else{
    //not a walk
    $func_status = 404;
  }
  $json = array(
    'status' => $func_status,
    'walk' => $walk
  );
  return $json;
}

function cancelWalk($id){
  global $conn;
  $walkResponce = getWalkByID($id);
  $walk = $walkResponce['walk'];
  $func_status = 500;
  if ($walkResponce["status"] == 200) {
    $sql = "UPDATE walk SET active = '0', status = '6' WHERE id = '".$id."'";
    $walk['active'] = 1;
    $walk['status'] = 6;
    $conn->query($sql);
  $func_status = 200;
  }else{
    //not a walk
    $func_status = 404;
  }
  $json = array(
    'status' => $func_status,
    'walk' => $walk
  );
  return $json;
}

function deleteWalk($id){
  global $conn;
  $walkResponce = getWalkByID($id);
  $walk = $walkResponce['walk'];
  $func_status = 500;
  if ($walkResponce["status"] == 200) {
    $sql = "UPDATE walk SET active = 0 WHERE id = '".$id."'";
    $walk['active'] = 0;
    $conn->query($sql);
  $func_status = 200;
  }else{
    //not a walk
    $func_status = 404;
  }
  $json = array(
    'status' => $func_status,
    'walk' => $walk
  );
  return $json;
}

function feedback($message, $phone_number, $time){
  global $conn;
  $json = array(
      'status' => 200
  );
  if ($phone_number != "none") {
    $walk = getWalkByUserPhoneNumber($phone_number);
    if ($walk["status"] == 200) {
      $sql = "INSERT INTO feedback (message, time, walk_id) VALUES ('".$message."','".$time."','".$walk["walk"]["id"]."')";
      $conn->query($sql);
      return $json;
    }
  }
  $sql = "INSERT INTO feedback (message, time) VALUES ('".$message."','".$time."')";
  $conn->query($sql);
  return $json;
}

function sendPush($deviceToken,$message){

  /*
  #######
  WE GOOD FOR RIGHT NOW
  #######
  */
  $passphrase = '';

  $ctx = stream_context_create();
  stream_context_set_option($ctx, 'ssl', 'local_cert', 'WalkPush.pem');
  stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

  // Open a connection to the APNS server
  $fp = stream_socket_client(
    'ssl://gateway.sandbox.push.apple.com:2195', $err,
    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

  if (!$fp){
    $error =  "Failed to connect: $err $errstr" . PHP_EOL;
    $json = array(
        'status' => 500,
        'error' => $error
    );
    $jsonstring = json_encode($json);
    echo $jsonstring;
    return;
  }

  // Create the payload body
  $body['aps'] = array(
    'alert' => $message,
    'sound' => 'default',
    );

  // Encode the payload as JSON
  $payload = json_encode($body);

  // Build the binary notification
  $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

  // Send it to the server
  $result = fwrite($fp, $msg, strlen($msg));

  if (!$result){
    $error =  "Message not delivered";
    $json = array(
        'status' => 500,
        'error' => $error
    );
    $jsonstring = json_encode($json);
    echo $jsonstring;
    // Close the connection to the server
    fclose($fp);
    return;
  }else{
    $json = array(
        'status' => 200
    );
    // Close the connection to the server
    fclose($fp);
    return $json;
  }
}

?>