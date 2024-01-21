<!DOCTYPE html>
<html>
    <head>
        <title> Login </title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>EventLandia</h1>
        </header>

        <?php
        $username = "";
        $password = "";

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "SELECT * FROM utenti WHERE Username = '$username'";
            $db_conn = mysqli_connect("localhost", "uNormal", "posso_leggere?", "biglietteria");

            if (!$db_conn) {
                echo "Connection failed to database";
            }

            $result = mysqli_query($db_conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                if ($password == $row['Password']) {
                    session_start();
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['credito'] = $row['Credito'];
                    $_SESSION['organizzatore'] = $row['Organizzatore'];
                    
                    if ($row['Organizzatore'] == 1) {
                        header('Location: gestione.php');
                    } else {
                        header('Location: acquisto.php');
                    }
                } else {
                    echo "Password errata!";

                    header('Location:  login.php');
                }
            } else {
                echo "Utente non esistente";

                header('Location:  login.php');
            }
        }
        ?>
        
        <ul class="navbar">
            <li class="nav-item"><a href="#">Home</a></li>
            <li class="nav-item"><a href="info.php">Info</a></li>
        </ul>
        <br>
        <br>

        <form method="POST" action="">
            <h2> Login </h2>
            <label for="username"> Username: </label>
            <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>
            <br><br>
            <label for="password"> Password: </label>
            <input type="password" name="password" id="password" value="<?php echo $password; ?>" required>
            <br><br>
            <input type="submit" name="submit" value="Invia">
            <input type="reset" name="reset" value="Cancella">
        </form>

        <footer>
            &copy; 2023 - Eventlandia - Created by Mario Cappello - File: <?php echo basename($_SERVER['PHP_SELF']); ?>
        </footer>
    </body>
</html>