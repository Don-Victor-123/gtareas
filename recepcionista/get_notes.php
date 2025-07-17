<?php
session_start();
require __DIR__.'/../config/db.php';
if($_SESSION['user']['role']!=='Recepcionista') exit('Acceso denegado');
$date = $_GET['date'] ?? date('Y-m-d');
$today = date('Y-m-d');
$response=[];
foreach(['pendiente','en_proceso','completada'] as $status){
    $stmt = $pdo->prepare('SELECT n.*,u.username FROM notes n JOIN users u ON n.user_id=u.id WHERE DATE(n.created_at)=? AND n.status=? ORDER BY n.created_at');
    $stmt->execute([$date,$status]);
    $rows = $stmt->fetchAll();
    $response[$status] = array_map(function($r) use ($date,$today) {
        $r['modificado'] = ($date!==$today) ? date('Y-m-d H:i:s') : '';
        return $r;
    }, $rows);
}
header('Content-Type: application/json');
echo json_encode($response);
?>