<?php
session_start();
require __DIR__.'/../config/db.php';
if($_SESSION['user']['role']!=='Recepcionista') exit('Acceso denegado');

$date = $_GET['date'] ?? date('Y-m-d');
$order = (isset($_GET['order']) && strtolower($_GET['order']) === 'asc') ? 'ASC' : 'DESC';

$response = [];
foreach(['pendiente','en_proceso','completada'] as $status){
    $stmt = $pdo->prepare('SELECT n.*,u.username FROM notes n JOIN users u ON n.user_id=u.id WHERE DATE(n.created_at)=? AND n.status=? ORDER BY n.created_at');
    $stmt->execute([$date,$status]);
    $response[$status] = $stmt->fetchAll();
}

if(isset($_GET['previous'])){
    $stmt = $pdo->query('SELECT n.*,u.username FROM notes n JOIN users u ON n.user_id=u.id WHERE DATE(n.created_at) < CURDATE() AND n.status IN (\'pendiente\',\'en_proceso\') ORDER BY n.created_at DESC');
    $response['previos'] = $stmt->fetchAll();
}

if(isset($_GET['completed'])){
    $stmt = $pdo->query("SELECT n.*,u.username FROM notes n JOIN users u ON n.user_id=u.id WHERE n.status='completada' ORDER BY n.created_at $order");
    $response['realizadas'] = $stmt->fetchAll();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
