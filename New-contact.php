<?php
session_start();

$conn = mysqli_connect('localhost', 'Michael', 'password123', 'dolphin_crm');
if ($conn->connect_error) {
    die('Connection Error: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function sanitizeInput($input, $conn)
    {
        return mysqli_real_escape_string($conn, trim(filter_var($input, FILTER_UNSAFE_RAW)));
    }

    $title = isset($_POST['title']) ? sanitizeInput($_POST['title'], $conn) : '';
    $fname = isset($_POST['fname']) ? sanitizeInput($_POST['fname'], $conn) : '';
    $lname = isset($_POST['lname']) ? sanitizeInput($_POST['lname'], $conn) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email'], $conn) : '';
    $phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone'], $conn) : '';
    $company = isset($_POST['company']) ? sanitizeInput($_POST['company'], $conn) : '';
    $type = isset($_POST['type']) ? sanitizeInput($_POST['type'], $conn) : '';
    $assignedto = isset($_POST['assignedto']) ? sanitizeInput($_POST['assignedto'], $conn) : '';

    if (empty($fname) || !preg_match('/^[a-zA-Z\s]+$/', $fname)) {
        echo 'Please enter a valid First Name.';
        exit();
    }

    if (empty($lname) || !preg_match('/^[a-zA-Z\s]+$/', $lname)) {
        echo 'Please enter a valid Last Name.';
        exit();
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Please enter a valid Email address.';
        exit();
    }

    if (empty($company) || !preg_match('/^[a-zA-Z\s]+$/', $company)) {
        echo 'Please enter a valid Company name.';
        exit();
    }

    if (empty($phone) || !is_numeric($phone)) {
        echo 'Please enter a valid Phone number.';
        exit();
    }

    if ($type === 'None') {
        echo 'Please select a valid Type.';
        exit();
    }

    if ($assignedto === 'None') {
        echo 'Please select a valid Assigned To.';
        exit();
    }

    if ($title === 'None') {
        echo 'Please select a valid Title.';
        exit();
    }

    $sql = 'INSERT INTO contacts (title, firstname, lastname, email, telephone, company, type, assigned_to) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $title, $fname, $lname, $email, $phone, $company, $type, $assignedto);

    if ($stmt->execute()) {
        $confirmationMessage = 'Data has been stored successfully!';
    } else {
        $confirmationMessage = 'Error: ' . $stmt->error;
    }

    $stmt->close();
}

$stmt = $conn->query('SELECT * FROM users');
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>New Contact</title>
    <link rel='stylesheet' href='newcontact.css'>
    <script src='newcontact.js'></script>
</head>
<body>
    <div class='grid-container'>
        <div id='header' class='grid-item'>
            <header>
                <div class='flex-container'>
                    <div id='head' class='flex-item'>
                        <h1>&#128044; Dolphin CRM </h1>
                    </div>
                </div>
            </header>
        </div>
        <main>
            <div id='body' class='grid-item'>
                <h2>New Contact</h2>
                <?php
                    if (isset($confirmationMessage)) {
                        echo '<p>' . $confirmationMessage . '</p>';
                    }
                ?>                      
                <form method='post' action='New-contact.php'>
                    <div class='form-group'>
                        <label>Title</label><br>
                        <select name='title' id='title'>
                            <option>Mr.</option>
                            <option>Mrs.</option>
                            <option>Ms.</option>
                            <option value='Dr.'>Dr</option>
                            <option value='Prof.'>Prof</option>
                        </select><br>

                        <label for='fname'>First Name</label><br>
                        <input type='text' id='fname' name='fname' placeholder='John' required><br>

                        <label for='lname'>Last Name</label><br>
                        <input type='text' id='lname' name='lname' placeholder='Doe' required><br>

                        <label for='email'>Email</label><br>
                        <input type='email' id='email' name='email' placeholder='something@example.com' required><br>

                        <label for='phone'>Telephone</label><br>
                        <input type='tel' id='phone' name='phone' placeholder='' required><br>

                        <label for='company'>Company</label><br>
                        <input type='text' id='company' name='company' placeholder='' required><br>

                        <label>Type</label><br>
                        <select name='type' id='type'>
                            <option>Sales Lead</option>
                            <option>Support</option>
                        </select><br>

                        <label>Assigned To</label><br>
                        <select name='assignedto' id='assignedto'>
                            <?php
                            while ($row = $stmt->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
                            }
                            ?>
                        </select><br><br>

                        <div id='button-section'>
                            <button type='submit' id='button' name='button'>Save</button>
                        </div>
                    </div>
                </form> 
            </div>
            <div id='show'></div>
        </main>
        <div id='sidebar' class='grid-item'>
            <div class='buttonaside' id='home'>
                <a href='dashboard.php'>Home</a>
            </div>
            <div class='buttonaside' id='New-contact'>
                <a href='New-contact.php'>New Contact</a>
            </div>
            <div class='buttonaside' id='users'>
                <a href='userlist.php'>Users</a>
            </div>
            <div class='buttonaside' id='logout'>
                <a href='logout.php'>Logout</a>
            </div>
        </div>
    </div>
</body>
</html>

