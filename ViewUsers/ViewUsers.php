<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dolphin CRM Users</title>
    <link rel="stylesheet" href="ViewUsers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>  
</head>
<body>
    <nav class = "sidebar">
         <div class ="dolphinLogo"></div>
           <ul class="menu">
              <li>
                  <a href="../Dolphin_CRM_Project1(draft2)/dashboard.html">
                      <i class="fas fa-home"></i>
                      <span> Home</span>
                  </a>
              </li>
              <li>
                   <a href="../New-contact.php">
                     <i class="far fa-user-circle"></i>
                     <span>New Contact</span>
                   </a>
              </li>
               <li>
                   <a href="ViewUsers.php">
                     <i class="fas fa-users"></i>
                     <span>Users</span>
                   </a>
             </li>
               <li>
                   <a href="../Logout/Logout.php">
                     <i class="fa fa-sign-out"></i>
                     <span>Logout</span>
                   </a>
              </li>
          </ul>
     </nav>
     <div class="main--content">
        <header>
            <div class="header--wrapper">
              <div class="header--title">
                <h3>Dolphin CRM</h3>
              </div>   
            </div>
        </header>

        <div class="users-header">
          <h1>Users</h1>
          <button class="add-user-btn" id="addUser">Add User</button>
        </div>
          <?php
              if (session_status() == PHP_SESSION_NONE) {
                  session_start();
              }

              if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                  echo 'Access denied';
                  exit;
              }

              $host = 'localhost:3307';
              $dbname = 'dolphin_crm';
              $username_db = 'DolphinAdmin';
              $password_db = 'password123';

              try {
                  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  $stmt = $pdo->prepare("SELECT * FROM users");
                  $stmt->execute();

                  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch(PDOException $e) {
                  echo "Error: " . $e->getMessage();
                  exit;
              }
          ?>
          <table>
              <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Created</th>
              </tr>
              <?php foreach ($users as $user): ?>
                  <tr>
                      <td><?php echo $user["firstname"] . " " . $user["lastname"]; ?></td>
                      <td><?php echo $user["email"]; ?></td>
                      <td><?php echo $user["role"]; ?></td>
                      <td><?php echo $user["created_at"]; ?></td>
                  </tr>
              <?php endforeach; ?> 
          </table>
        </div>
</body>
</html>
