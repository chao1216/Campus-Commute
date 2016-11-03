<!--
Chao Lin
Maggie Koella
Liz Zuniga
-->

<html>
  <head>
    <title>Board</title>
    <link rel = "stylesheet" href = "tableStyle.css">
    <link rel = "stylesheet" href = "pStyle.css">
  </head>
  <body>
    <div id = "dheader">
      <div class = "container">
        <div class = "title">
           Connect with your commute!
        </div>
        <div class = "nav">
           <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="dForm.html">Driver Sign-Up</a></li>
              <li><a href="pForm.html">Passenger Sign-Up</a></li>
            </ul>
          </div>
       </div>
    </div>
    <div id ="note">
      Thank you for registering! <br>Below you can see a list of drivers going to the same region as your destination.
    </div>
  </body>

</html>

<?php
require_once 'login.php';
$connection = new mysqli($hn, $un, $pw, $db);
if($connection -> connect_error) die('failed to connect');

if(isset($_POST['fName']) &&
   isset($_POST['lName']) &&
   isset($_POST['phone']) &&
   isset($_POST['email']) &&
   isset($_POST['campus']) &&
   isset($_POST['region']) &&
   isset($_POST['city']) &&
   isset($_POST['state']) &&
   isset($_POST['departure']) &&
   isset($_POST['luggage']) &&
   isset($_POST['gasQ']) &&   
   isset($_POST['driverQ']) &&
   isset($_POST['smokeQ']) &&
   isset($_POST['petQ']) &&
   isset($_POST['carSickQ']))
{

   $fName = get_post($connection, 'fName');
   $lName = get_post($connection, 'lName');
   $phone = get_post($connection, 'phone');
   $email = get_post($connection, 'email');
   $campus = get_post($connection, 'campus');

   $region = get_post($connection,'region');
   $city = get_post($connection,'city');
   $state = get_post($connection,'state');
   $departure = get_post($connection,'departure');

   $luggage = get_post($connection,'luggage');
   $gasQ = get_post($connection,'gasQ');
   $driverQ = get_post($connection,'driverQ');
   $smokeQ = get_post($connection,'smokeQ');
   $petQ = get_post($connection,'petQ');
   $carSickQ = get_post($connection,'carSickQ');

   /*
   All the check are used to ensure that 
   no duplicate values are inserted into the database
   */
   $campusCheck = "SELECT * FROM university WHERE campus='$campus'";
   $nameCheck = "SELECT * FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                 AND userInfo.userTypeId = driverOrPassenger.userTypeId
                 AND driverOrPassenger.userType='passenger'";
   $emailCheck = "SELECT * FROM contact WHERE email='$email'";

   $campusResult = $connection->query($campusCheck);
   $nameResult = $connection->query($nameCheck);
   $emailResult = $connection->query($emailCheck);

   $campusRows = $campusResult->num_rows;
   $nameRows = $nameResult->num_rows;
   $emailRows = $emailResult->num_rows;

   if($campusRows==0){
     $query = "INSERT INTO university(campus) VALUES('$campus')";
     $result = $connection->query($query);
   };

   if($nameRows==0){
     $query = "INSERT INTO userInfo(fName,lName,schoolId,userTypeId)
                VALUES('$fName','$lName',(SELECT schoolId FROM university WHERE campus='$campus'),
                      (SELECT userTypeId FROM driverOrPassenger WHERE userType='passenger'))";
     $result = $connection->query($query);
   };

   if($emailRows==0){
     $query = "INSERT INTO contact(phone,email,userId) VALUES('$phone','$email',
                          (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                           AND userInfo.userTypeId=driverOrPassenger.userTypeId
                           AND driverOrPassenger.userType='passenger'))";
     $result = $connection->query($query);
   };

   
   $cityCheck = "SELECT * FROM destination WHERE city='$city'";
   $stateCheck = "SELECT * FROM destination WHERE state='$state'";
   $departureCheck = "SELECT * FROM departure WHERE departureDate='$departure'";

   $cityResult = $connection->query($cityCheck);
   $stateResult = $connection->query($stateCheck);
   $departureResult = $connection->query($departureCheck);

   $cityRows = $cityResult->num_rows;
   $stateRows = $stateResult->num_rows;
   $departureRows = $departureResult->num_rows;

   if($cityRows==0 || $stateRows==0){
     $query = "INSERT INTO destination(city,state,regionId) VALUES('$city','$state',
                              (SELECT regionId FROM region WHERE name = '$region'))";
     $result = $connection->query($query);
   }

   if($departureRows==0){
     $query = "INSERT INTO departure(departureDate) VALUES('$departure')";
     $result = $connection->query($query);
   }


   $destinationInfo = array("INSERT INTO whoGoWhere(userId,destinationId) VALUES(
                              (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                              AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'),
                              (SELECT destinationId FROM destination WHERE city='$city' AND state='$state'))",
                            "INSERT INTO whoDepartWhen(userId, dateId) VALUES(
                              (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                              AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'),
                              (SELECT dateId FROM departure WHERE departureDate='$departure'))");
     
   for($j=0; $j<count($destinationInfo); $j++){
      $result2 = $connection->query($destinationInfo[$j]);
   };  



   $answerCheck = "SELECT * FROM response WHERE userId = (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                  AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger')";
   $answerResult = $connection->query($answerCheck);
   $answerRows = $answerResult->num_rows;

   if($answerRows!=6){
       $answers = array("INSERT INTO response(questionId, y_n_Id, numId, userId) VALUES(
                    (SELECT questionId FROM questions WHERE question='smokeQ'),
                    (SELECT y_n_Id FROM yesOrNoAnswer WHERE answer='$smokeQ'), null,
                    (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'))",
                    "INSERT INTO response(questionId, y_n_Id, numId, userId) VALUES(
                    (SELECT questionId FROM questions WHERE question='gasQ'),
                    (SELECT y_n_Id FROM yesOrNoAnswer WHERE answer='$gasQ'), null,
                    (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'))",
                    "INSERT INTO response(questionId, y_n_Id, numId, userId) VALUES(
                    (SELECT questionId FROM questions WHERE question='driverQ'),
                    (SELECT y_n_Id FROM yesOrNoAnswer WHERE answer='$driverQ'), null,
                    (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'))",
                    "INSERT INTO response(questionId, y_n_Id, numId, userId) VALUES(
                    (SELECT questionId FROM questions WHERE question='petQ'),
                    (SELECT y_n_Id FROM yesOrNoAnswer WHERE answer='$petQ'), null,
                    (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'))",
                    "INSERT INTO response(questionId, y_n_Id, numId, userId) VALUES(
                    (SELECT questionId FROM questions WHERE question='carSickQ'),
                    (SELECT y_n_Id FROM yesOrNoAnswer WHERE answer='$carSickQ'), null,
                    (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'))",
                    "INSERT INTO response(questionId, y_n_Id, numId, userId) VALUES(
                    (SELECT questionId FROM questions WHERE question='luggage'), NULL,
                    (SELECT numId FROM numericAnswer WHERE answer='$luggage'),
                    (SELECT userId FROM userInfo,driverOrPassenger WHERE fName='$fName' AND lName='$lName'
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId AND driverOrPassenger.userType='passenger'))");

      for($k=0; $k<count($answers); $k++){
        $result3 = $connection->query($answers[$k]);
      }; 
   };

   
    echo <<<_END
    <table id="users" border ='1'>
    <tr>
    <th colspan='2'>Name</th>
    <th colspan='2'>Contact</th>
    <th colspan='3'>Destination</th>
    <th colspan='1'>University</th>
    <th colspan='1'>Vehicle</th>
    <th colspan='2'>Additional Information</th>
    <tr>
    <th>First</th>
    <th>Last</th>
    <th>Phone</th>
    <th>Email</th>
    <th>City</th>
    <th>State</th>
    <th>Departure Date</th>
    <th>Campus</th>
    <th>Type</th>
    <th>Willing to travel w/ smoker?</th>
    <th>Travling w/ pets?</th>
    </tr>
_END;

    $userContent = "SELECT DISTINCT * FROM userInfo, whoGoWhere,destination,region,driverOrPassenger
                    WHERE whoGoWhere.userId = userInfo.userId
                    AND whoGoWhere.destinationId = destination.destinationId 
                    AND destination.regionId = region.regionId 
                    AND region.name='$region'
                    AND userInfo.userTypeId = driverOrPassenger.userTypeId
                    AND driverOrPassenger.userType = 'driver'";
    $userResult=$connection->query($userContent);
    $userRows = $userResult->num_rows;

    for($i=0; $i<$userRows; $i++){
      $userResult->data_seek($i);
      $userRow = $userResult->fetch_array(MYSQLI_NUM);

      $destinationContent="SELECT DISTINCT city,state FROM destination,whoGoWhere,userInfo,driverOrPassenger 
                          WHERE destination.destinationId = whoGoWhere.destinationId
                          AND userInfo.userId = whoGoWhere.userId
                          AND userInfo.userTypeId = driverOrPassenger.userTypeId
                          AND driverOrPassenger.userType='driver'
                          AND userInfo.fName='$userRow[1]'
                          AND userInfo.lName='$userRow[2]'";
      $destinationResult1=$connection->query($destinationContent);
      $destinationResult2=$connection->query($destinationContent);
      $city = $destinationResult1->fetch_assoc()['city'];
      $state = $destinationResult2->fetch_assoc()['state'];

      $contactContent="SELECT DISTINCT phone,email FROM contact,userInfo,driverOrPassenger
                       WHERE contact.userId=userInfo.userId
                       AND userInfo.userTypeId = driverOrPassenger.userTypeId
                       AND driverOrPassenger.userType='driver'
                       AND userInfo.fName='$userRow[1]'
                       AND userInfo.lName='$userRow[2]'";
      $contactResult1=$connection->query($contactContent);
      $contactResult2=$connection->query($contactContent);
      $phone = $contactResult1->fetch_assoc()['phone'];
      $email = $contactResult2->fetch_assoc()['email'];

      $campusContent="SELECT campus FROM university,userInfo,driverOrPassenger
                     WHERE university.schoolId = userInfo.schoolId
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId
                     AND driverOrPassenger.userType='driver'
                     AND userInfo.fName='$userRow[1]'
                     AND userInfo.lName='$userRow[2]'";
      $campusResult=$connection->query($campusContent);
      $campus = $campusResult->fetch_assoc()['campus'];

      $departureContent="SELECT DISTINCT departureDate FROM departure,userInfo,whoDepartWhen,driverOrPassenger
                         WHERE departure.dateId = whoDepartWhen.dateId
                         AND userInfo.userId = whoDepartWhen.userId
                         AND userInfo.userTypeId = driverOrPassenger.userTypeId
                         AND driverOrPassenger.userType='driver'
                         AND userInfo.fName='$userRow[1]'
                         AND userInfo.lName='$userRow[2]'";
      $departureResult=$connection->query($departureContent);
      $departure = $departureResult->fetch_assoc()['departureDate'];
      //$departureDates = $departureResult->fetch_array(MYSQLI_NUM);


      $vehicleContent = "SELECT type FROM vehicleInfo,whoOwnWhatVehicle,userInfo,driverOrPassenger
                         WHERE whoOwnWhatVehicle.vehicleId = vehicleInfo.vehicleId
                         AND whoOwnWhatVehicle.userId = userInfo.userId
                         AND userInfo.userTypeId=driverOrPassenger.userTypeId
                         AND driverOrPassenger.userType='driver'
                         AND userInfo.fName='$userRow[1]'
                         AND userInfo.lName='$userRow[2]'";
      $vehicleResult = $connection->query($vehicleContent);
      $vehicle = $vehicleResult->fetch_assoc()['type'];

      
      $questions = array('smokeQ','petQ');
      for($j=0;$j<count($questions);$j++){
         $query="SELECT answer FROM yesOrNoAnswer, response, questions, userInfo,driverOrPassenger
                     WHERE yesOrNoAnswer.y_n_Id = response.y_n_Id 
                     AND questions.questionId = response.questionId 
                     AND questions.question='$questions[$j]' 
                     AND response.userId = userInfo.userId 
                     AND userInfo.userTypeId = driverOrPassenger.userTypeId
                     AND driverOrPassenger.userType='driver'
                     AND userInfo.fName='$userRow[1]'
                     AND userInfo.lName='$userRow[2]'";
          $result=$connection->query($query);
          $answers[$j] = $result->fetch_assoc()['answer'];
      };


      echo <<<_END
      <tr>
      <td>$userRow[1]</td>
      <td>$userRow[2]</td>
      <td>$phone</td>
      <td>$email</td>
      <td>$city</td>
      <td>$state</td>
      <td>$departure</td>
      <td>$campus</td>
      <td>$vehicle</td>
      <td>$answers[0]</td>
      <td>$answers[1]</td>
      </tr>
_END;
    };
    echo "</table>";


};

$campusResult->close();
$nameResult->close();
$emailResult->close();
$cityResult->close();
$stateResult->close();
$departureResult->close();
$result->close();
$result2->close();
$result3->close();

function get_post($connection,$var)
{
 return $connection->real_escape_string($_POST[$var]);
}

?>
