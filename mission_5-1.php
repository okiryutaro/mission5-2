<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
    // データベース接続
$dsn = 'mysql:dbname=**********;host=localhost';
$user = '*********';
$password = '**********';
//データベースにアクセス・エラーモードを設定
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブルが存在しない時、テーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS tb5"
	."("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
  . "comment TEXT,"
  . "date TEXT,"
  . "password varchar(10)"
	.");";
  $stmt = $pdo->query($sql);
  ?>

<?php     
// <!-- 削除機能 -->
  if(!empty($_POST["delnum"]) && !empty($_POST["delpass"])){        // 削除番号とパスワードがあったら
    $delnum = $_POST["delnum"];
    $delpass = $_POST["delpass"];
    
    $sql = "SELECT*FROM tb5";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){
      if($delnum == $row['id'] && $delpass == $row['password']){
        $sql = "delete from tb5 WHERE id=:id";
        $stmt = $pdo -> prepare($sql);
        $stmt ->bindParam(':id',$delnum,PDO::PARAM_INT);
        $stmt -> execute();
            }
        }
    }
  
   //  <!-- 編集機能 -->
  if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){       // 編集番号とパスワードがあったら
    $editnum = $_POST["editnum"];
    $editpass = $_POST["editpass"];
    
    $sql = "SELECT*FROM tb5";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach($results as $row){
      // 番号とパスワードが一致しているか
      if($editnum == $row['id'] && $editpass == $row['password']){
        $number = $row['id'];
        $editname = $row['name'];
        $editcomment = $row['comment'];
                }
            }
        }
  
   //  <!-- 投稿機能 -->
  //  名前・コメント・パスワードがセットされているか
  if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
    //入力情報の受け取り
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    $password = $_POST["password"];
    // 新規追加 
    if(empty($_POST["number"])){
      $sql = $pdo -> prepare("INSERT INTO tb5 (name,comment,date,password) VALUES (:name, :comment, :date, :password)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':password', $password, PDO::PARAM_STR);
      $sql -> execute();
      
    }else{
    //  編集
      $editNum = $_POST["number"];
      $sql = "SELECT*FROM tb5";
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      
      foreach($results as $row){
         //編集番号とidが一致しているか
        if($editNum == $row['id']){
          $sql = "UPDATE tb5 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':id', $editNum, PDO::PARAM_INT);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
          $stmt->bindParam(':date', $date, PDO::PARAM_STR);
          $stmt->bindParam(':password', $password, PDO::PARAM_STR);
          $stmt->execute();
        }
      }
    }
  }

  ?>
  <h1 style="color:lightgreen"; font-size:30px;">自分が作れる１番うまい料理は？</h1>
         <!-- 投稿フォーム -->
<form action="" method="post">
        <input type="text" name="name" placeholder="名前" value= 
         "<?php 
        if(isset($editname)){
            echo $editname;
        }?>"> <br>
        <input type="text" name="comment" placeholder="コメント" value= 
        "<?php
        if(isset($editcomment)){
            echo $editcomment;
        }?>"> 
        <input type="hidden" name="number" placeholder="" value= 
         "<?php
        if(isset($number)){
            echo $number;
        }?>"> <br>
        <input type="text" name="password" placeholder="パスワード"> <br>
        <input type="submit" name="submit" value="投稿" style="background-color: white; color:red;">
    </form>
          <!-- 削除フォーム -->
    <form action="" method="post">
      <input type="number" name="delnum" placeholder="削除番号"> <br>
      <input type="text" name="delpass" placeholder="パスワード"> <br>
      <input type="submit" name="delsub" value="削除" style="background-color: white; color:red;">
    </form>
          <!-- 編集フォーム -->
    <form action="" method="post">
      <input type="number" name="editnum" placeholder="編集番号"> <br>
      <input type="text" name="editpass" placeholder="パスワード"> <br>
      <input type="submit" name="editsub" value="編集" style="background-color: white; color:red;">
    </form>

    <?php
     $sql = "SELECT*FROM tb5";
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();

     foreach($results as $row){
      echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
		echo $row['date'].'<br>';
       echo "<hr>";

     }
    ?>
</body>
</html>