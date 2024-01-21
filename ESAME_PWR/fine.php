<!DOCTYPE html>
<html>
<head>
    <title>Fine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Eventlandia</h1>
    </header>
    
    <ul class="navbar">
        <li class="nav-item"><a href="home.php">Home</a></li>
        <li class="nav-item"><a href="info.php">Info</a></li>
        <li class="nav-item"><a href="acquisto.php">Compra</a></li>
        <?php
            session_start();

            if (isset($_SESSION['username'])) {
                if ($_SESSION['organizzatore'] == 1) {
                    echo '<li class="nav-item"><a href="gestione.php">Gestisci</a></li>';
                } 
                
                echo '<li class="nav-item last"><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li class="nav-item last"><a href="login.php">Login</a></li>';
            } 
        ?>
    </ul>

    <main>
        <p class="user">
            <?php
                if (isset($_SESSION['username'])) {
                    echo 'User: ' . $_SESSION['username'];
                    echo '<br>Credito: ' . $_SESSION['credito'];
                } else {
                    echo 'User: NA <br>';
                    echo 'Credito: 0';
                }
            ?>
        </p>
    </main>

    <?php
        if (isset($_SESSION['totale'])) {
            $total = $_SESSION['totale'];
            $username = $_SESSION['username'];
            $sql = "UPDATE utenti SET Credito=Credito-$total WHERE Username='$username'";
            $db_conn = mysqli_connect("localhost", "uPower", "SuperPippo!!!", "biglietteria");

            if (!$db_conn) {
                echo "Connection failed to database";
            }

            if (mysqli_query($db_conn, $sql)) {
                foreach ($_SESSION['cart'] as &$item) {
                    $id = $item['id'];
                    $qty = $item['qty'];
                    $sql = "UPDATE eventi SET Quantità=Quantità-$qty WHERE ID=$id";

                    mysqli_query($db_conn, $sql);
                }
                $_SESSION['credito'] -= $total;
                $_SESSION['cart'] = [];
                
                echo '<p> L\'acquisto è andato a buon fine! </p>';
            } else {
                header('Location: conferma.php');
            }  
        }
    ?>

    <footer>
        &copy; 2023 - Eventlandia - Created by Mario Cappello - File: <?php echo basename($_SERVER['PHP_SELF']); ?>
    </footer>
</body>
</html>