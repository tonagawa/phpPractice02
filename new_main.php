<?php

    if(isset($_POST['proc']))  // POSTリクエストを受け取った場合
    {
        if($_POST['proc'] === 'register')
        {

            // SQL文の作成
            $r_sql = "INSERT INTO practice (id, number, text, flag, ref_num) VALUES ";
            foreach($_POST['data'] as $row)
            {
                $r_sql .= "(".$row['id'].", 999,'".$row['text']."',".$row['flag'].",".$row['ref_num'].")";

                if(next($_POST['data']))
                {
                    $r_sql .= ",";
                }

            }
            $r_sql .= " ON DUPLICATE KEY UPDATE text = VALUES(text), flag = VALUES(flag), ref_num = VALUES(ref_num);";

            // クエリの実行
            $registeredResult = [];
            $registeredResult = DB::DB_Connect($r_sql);

            header('Content_type:application/json');
            echo json_encode($registeredResult);
            exit();
        }
    }
   

    // DBにデータが存在する場合は、表示用にデータを取得する
    $data = DB::DB_DataExistCheck();
    

    // 登録用テキストボックスと、チェックボックスの表示
    for($i = 1; $i <= 5; $i++){
        echo '<div>';
        echo '<span>テキスト',$i,'</span>';
        echo '<span><input type="text" id="input',$i,'"';

        // DBにデータが存在した場合、'text'列の値をテキストボックスの value に追加する（エスケープ処理も入れる）
        if(isset($data[$i-1]))
        {
            echo ' value="',DB::h($data[$i-1]['text']),'"';
        }

        echo '></span>';
        echo '<label for="check',$i,'"><span><input type="checkbox" id="check',$i,'"';

        // DBにデータが存在した場合、'flag'列の値が 1 ならチェックボックスに checkd を追加する
        if(isset($data[$i-1]) && $data[$i-1]['flag'] == 1)
        {
            echo ' checked';
        }
        echo '>使用する</span></label>';
        echo '</div>';
    }

    // 「登録」ボタンと「クリア」ボタン
    echo '<div>';
    echo '<input type="button" id="btnAdd" value="登録" onclick="RegisterData()">';
    echo '<input type="button" id="btnClear" value="クリア" onclick="Clear()">';
    echo '</div>';


    class DB
    {
        public static function DB_Connect($sql)
        {
            //データベース接続用変数
            $host     = '*****';
            $username = '*****';
            $password = '*****';
            $dbname   = '*****';

            // DBへ接続
            $mysqli = new mysqli($host, $username, $password, $dbname);

            // 接続チェック
            if($mysqli->connect_error)
            {
                return false;
            }
            else
            {
                $mysqli->set_charset('utf8');
            }

            $result = $mysqli->query($sql);

            return $result;
        }

        public static function DB_DataExistCheck()
        {
            // DBにデータが存在するかチェック
            $sql = "SELECT * FROM practice ORDER BY id"; 
            $dbResults = DB::DB_Connect($sql);

            // データが存在する場合は、HTML表示用変数 $data にDBの値をセット
            if($dbResults->num_rows > 0)
            {   
                for($i = 0; $i < 5; $i++)
                {
                    $results[] = $dbResults->fetch_assoc();
                }
            }
            else
            {
                $results = null;
            }

            return $results;
        }

        public static function h($str)
        {
            return htmlspecialchars($str);
        }
    }
?>