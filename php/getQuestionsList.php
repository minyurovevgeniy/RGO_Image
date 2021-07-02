  <?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  //$testToken=$_POST['test'];
  $testToken=3;

  $stmt=$pdo->prepare("SELECT `test_id` FROM `tests` WHERE `test_token`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$testToken)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $test=$row['test_id'];

  $questions=array();
  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=? ORDER BY `question_id` ASC");
  $stmt->execute(array($test));

  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
    $questions['questions'][]=array
    (
      "question_text"=>iconv("cp1251","utf-8",$row['question_text']),
      "question_id"=>$row['question_id']
    );
  }

  echo json_encode($questions);

 ?>
