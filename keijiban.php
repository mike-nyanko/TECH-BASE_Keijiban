<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"http://www.w3.org/TR/html4/loose.dtd"">
<html lang = "ja">
    <head>
        <meta charset = 'utf-8'>
    </head>
    
    <?php
        $edit_number = null;
        $line_number = null;
        $edit_name = null;
        $edit_comment = null;
        $elm = null;
        
        $dsname = 'DATABASENAME';
	    $user = 'USERNAME';
	    $user_password = 'PASSWORD';
	    $pdo = new PDO($dsname, $user, $user_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    
	    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    	." ("
    	. "id INT AUTO_INCREMENT PRIMARY KEY,"
    	. "name char(32),"
    	. "comment TEXT,"
    	. "date char(24),"
    	. "password TEXT"
    	.");";
    	$stmt = $pdo -> query($sql);

    	/*$sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        		echo $row['id'].',';
        		echo $row['name'].',';
        		echo $row['comment'].',';
        		echo $row['date'].',';
        		echo $row['password'].'<br>';
        }*/
        
    	//nameフォーム, commentフォーム, sub_passwordフォーム, edit_number2フォームが全て空でない時
        if(!empty ($_POST["name"]) && ($_POST["comment"]) && ($_POST["sub_password"]) && empty ($_POST["edit_number2"])) {      
            $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
        	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
        	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
        	$name = $_POST["name"];
        	$comment = $_POST["comment"];
        	$date = date("Y/m/d H:i:s");
        	$password = $_POST["sub_password"];
        	$sql -> execute();
        }
        
        if(!empty ($_POST["del_number"]) && ($_POST["del_password"])) {
            $id = $_POST["del_number"];
            $del_password = $_POST["del_password"];
            $sql = 'SELECT * FROM mission5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();                            
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $password = $row['password'];
            }

            if($del_password == $password) {
                $sql = 'delete from mission5 where id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
            	$stmt->execute();
            } else {
                echo "パスワードが一致しません。";  
            }
        }
        
        if(!empty ($_POST["edit_number"]) && ($_POST["edit_password"])) {
            $id = $_POST["edit_number"];
            $edit_password = $_POST["edit_password"];
            $sql = 'SELECT * FROM mission5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();                            
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $password = $row['password'];
            }

            if($edit_password == $password) {
                foreach ($results as $row) {
                    $edit_name = $row['name'];
                    $edit_comment = $row['comment'];
                    $edit_number2 = $id;
                }
            }
        }
        
        if(!empty ($_POST["name"]) && ($_POST["comment"]) && ($_POST["sub_password"]) && ($_POST["edit_number2"])) {
            $id = $_POST["edit_number2"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $password = $_POST["sub_password"];
            $sql = 'UPDATE mission5 SET name=:name, comment=:comment, date=:date, password=:password WHERE id=:id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute(); 
        }
    ?>
    
    <body>
        <form method = "POST" action = "">
        <div name = "Form">
        <p>
            お名前：<input type="text" name="name" placeholder="お名前" value="<?php echo $edit_name;?>"><br>
           <!-- コメント：<textarea name = "comment" cols = "20" rows = "3" placeholder="コメント"></textarea><br> -->
           コメント：<input type="text" name="comment" placeholder="コメント" value="<?php echo $edit_comment;?>"><br>
           Password：<input type="text" name="sub_password" placeholder="パスワード"><br>
           <input type="hidden" name="edit_number2" placeholder="番号" size="1" value="<?php echo $edit_number2;?>"><br>
           <input type="submit" name="submit" value="投稿"><br>
       
        </p>
        <p>
           編集するコメント番号：<input type="number" name="edit_number" placeholder="番号" size="1"><br>
           Password：<input type="text" name="edit_password" placeholder="パスワード"><br>
           <input type="submit" name="edit" value="編集"><br>
        </p>
        <p>
           削除するコメント番号：<input type="number" name="del_number" placeholder="番号" size="1"><br>
           Password：<input type="text" name="del_password" placeholder="パスワード"><br>
           <input type="submit" name="delete" value="削除"><br>
        </p>
        </div>
        </form>
    </body>
</html>