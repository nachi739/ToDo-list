<?php 
//文字コード（utf-8）変更
function h($v) {
	return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

//変数
$FILE = 'todo.txt';

$id = uniqid();

//時間
date_default_timezone_set('Japan');
$date = date('Y年m月d日H時i分');

//テキスト
$text = '';
$DATA = [];
$BOARD = [];

//$FILEファイル存在時のみ
if(file_exists($FILE)) {
	$BOARD = json_decode(file_get_contents($FILE));
}

//$_SERVERは送信されたサーバーの情報を得る
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	//$_POSTはHTTPリクエストで渡された値を取得する
	//リクエストパラメーターが空でなければ
	if(!empty($_POST['txt'])) {
		//投稿ボタンが押された場合
		$text = $_POST['txt'];

		//新規データ
		$DATA = [$id, $date, $text];
		//新規データを全体配列に代入する
		$BOARD[] = $DATA;

		//全体配列をファイルに保存する
		file_put_contents($FILE, json_encode($BOARD));

	}else if(isset($_POST['del'])) {
		//削除ボタンが押された場合

		//新しい全体配列を作る
		$NEWBOARD = [];

		//削除ボタンが押される時、既に$BOARDは存在している
		foreach($BOARD as $DATA){
			//$_POST['del']には各々のidが入っている
			//保存しようとしている＄DATA[0]が送信されてきたidと等しくない時だけ配列に入れる
			if($DATA[0] !== $_POST['del']) {
				$NEWBOARD[] = $DATA;
			}
		}
		//全体配列をファイルに保存する
		file_put_contents($FILE, json_encode($NEWBOARD));
	}

	//header()で指定したページにリダイレクト
	//今回は今と同じ場所にリダイレクト
	header('Location: ' .$SERVER['SCRIPT_NAME']);
	//プログラム終了
	exit;
}

?>


<!DOCTYPE html>
<html lang= "ja">
<head>
	<meta name= "viewport" content= "width=device-width, initial-scale= 1.0">
	<meta http-equiv= "content-type" charset= "utf-8">
	<title>ToDo</title>
</head>
<body>
	<h1>ToDo</h1>

	<section class= "main">
		<h2>ToDoに投稿する</h2>

		<!--投稿-->
		<form method= "post">
			<input type= "text" name= "txt">
			<input type= "submit" value= "投稿">
		</form>

		<table style= "border-collapse: collapse">
		<!--tableの中でtr部分をループ-->
		<?php foreach((array)$BOARD as $DATA): ?>
		<tr>
		<form method= "post">
			<td>
				<!--テキスト-->
				<?php echo h($DATA[2]); ?>
			</td>
			<td>
				<!--日時-->
				<?php echo $DATA[1]; ?>
			</td>
			<td>
				<!--削除-->
				<!--この時その投稿のidサーバーに送信される-->
				<input type= "hidden" name= "del" value= "<?php echo $DATA[0]; ?>">
				<input type= "submit" value= "削除">
			</td>
		</form>
		</tr>
		<?php endforeach; ?>
		</table>
	</section>
</body>
</html>






































