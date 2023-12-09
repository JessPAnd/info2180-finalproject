<!DOCTYPE html>
<html>
    <head>
        <title>Dolphin CRM</title>
        <meta charset= "utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="ViewUsers.css">
        <script src="ViewUsers.js"></script>
    </head>

    <body>
        <header class="container">
            <img class="dolphin" src="Dolphin.png"> 
            <h4>Dolphin CRM</h4>
        </header>
        <main>
            <aside class ="sidebar">
                <nav>
                    <ul>
                        <li>
                            <img class = "homeimg" src="icons8-home-50.png" alt="Home">
                            <p><a href="link to dashboard">Home</a></p>
                        </li>
                        <li>
                            <img class = "contactimg" src="icons8-contacts-50.png" alt="New Contact">
                            <p><a href="link to new contant">New Contact</a></p>
                        </li>
                        <li>
                            <img class = "usersimg" src="icons8-users-30.png" alt="Users">
                            <p><a href="ViewUsers.html">Users</a></p>
                        </li>
                        <hr>
                        <li>
                            <img class = "logoutimg" src="icons8-logout-48.png" alt="Logout">
                            <p><a href="Logout.php">Logout</a></p>
                        </li>
                    </ul>
                </nav>
            </aside>
    
        <div>
            <h1><b>Users</b></h1>
            <button id="addUser">+ Add User</button>
        </div>
        <?php
            session_start();
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') 
            {
                print('Access denied');
            }

            $host = 'localhost';
            $dbname = 'dolphin_crm';
            $username_db = 'admin';
            $password_db = 'password123';

            try 
            {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare("SELECT * FROM users");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } 
            catch(PDOException $e) 
            {
                echo "Error: " . $e->getMessage();
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
