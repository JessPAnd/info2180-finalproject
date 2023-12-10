
<?php
    session_start();
    $host = 'localhost:3307';
    $username = 'DolphinAdmin';
    $password = 'password123';
    $dname = 'dolphin_crm';
    $sessionId = session_id();
    $users=[];
    $conn = new PDO("mysql:host=$host;dbname=$dname;charset=utf8mb4", $username, $password);
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $validUsername = isset($_POST['username']) ? filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) : '';
    $validPassword = isset($_POST['password']) ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) : '';


    $stmt = $conn->prepare('SELECT * FROM users');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            $hashedInputPassword = md5($validPassword);
            if ($user['firstname'] === $validUsername && $user['password'] === $hashedInputPassword){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                echo json_encode(['status' => 'success']);
                break;
                
            } else {
                echo json_encode(['status' => 'fail']);
                }
        
        }
        

            
        }
?>

