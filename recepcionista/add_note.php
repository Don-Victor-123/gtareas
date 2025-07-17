<?php
session_start();
require __DIR__.'/../config/db.php';
if($_SESSION['user']['role']!=='Recepcionista') exit(json_encode(['success'=>false]));
$user_id = $_SESSION['user']['id'];
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$date = $_POST['date'] ?? date('Y-m-d');
$created_at = ($date === date('Y-m-d')) ? date('Y-m-d H:i:s') : $date . ' 00:00:00';
$stmt = $pdo->prepare('INSERT INTO notes(user_id,title,content,status,created_at) VALUES(?,?,?,?,?)');
$stmt->execute([$user_id,$title,$content,'pendiente',$created_at]);
echo json_encode(['success'=>true]);
?>
