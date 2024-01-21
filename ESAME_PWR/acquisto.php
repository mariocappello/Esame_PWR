<!DOCTYPE html>
<html>
    <head>
        <title> Acquisto </title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>EventLandia</h1>
        </header>
        
        <?php
            session_start();

            if (!isset($_SESSION['username'])) {
                header('Location: error.php');
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (isset($_GET['reset'])) {
                    if ($_GET['reset'] == 1) {
                        $_SESSION['cart'] = [];
                    }
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                foreach ($_POST as $key => $value) {
                    if (strpos($key,"qt_event") === 0) {
                        $product_id = substr($key, 9);
                        $qty = $value;

                        $found = false;
                        foreach ($_SESSION['cart'] as &$item) {
                            if ($item['id'] == $product_id) {
                                $item['qty'] = $qty;
                                $found = true;
                                break;
                            }
                        }

                        if (!$found) {
                            $_SESSION['cart'][] = [
                                'id'=> $product_id,
                                'qty'=> $qty
                            ];
                        }
                    }
                }
            }
        ?>

        <ul class="navbar">
            <li class="nav-item"><a href="home.php">Home</a></li>
            <li class="nav-item"><a href="info.php">Info</a></li>
            <li class="nav-item"><a href="#">Compra</a></li>
            <?php
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
        <br>
        <br>

        <main>
            <p class="user">
                <?php                    
                    if (isset($_SESSION['username'])) {
                        echo "User: " . $_SESSION['username'];
                        echo "<br>Credito: " . $_SESSION['credito'];
                    } else {
                        echo "User: NA <br>";
                        echo "Credito: 0";
                    }
                ?>
            </p>
            <h2> Eventi disponibili </h2>
            <table>
                <tr> 
                    <th> Evento </th>
                    <th> Data </th>
                    <th> Biglietti </th>
                    <th> Prezzo  </th>
                    <th> </th>
                </tr>
                <?php   
                    $query = 'SELECT * FROM eventi';
                    $db_conn = mysqli_connect("localhost", "uPower", "SuperPippo!!!", "biglietteria");

                    if (!$db_conn) {
                        die('Connessione fallita al database: ' . mysqli_error($db_conn));
                    }

                    $sql = mysqli_query($db_conn, $query);

                    if (mysqli_num_rows($sql) > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) {
                            if (strtotime($row['Data']) > strtotime(date('Y-m-d'))) {
                                $found = false;
                                foreach ($_SESSION['cart'] as &$item) {
                                    if ($item['id'] == $row['ID']) {
                                        $qty = $item['qty'];
                                        $found = true;
                                        break;
                                    }
                                }

                                if (!$found) {
                                    $qty = 0;
                                }

                                echo '<tr><form method="post">';
                                echo '<td>' . $row['Nome'] .'</td><td>'. $row['Data'] . '</td><td>'. $row['Quantità'] . '</td><td>' . $row['Costo'];
                                echo '</td><td><input type="number" name="qt_event_' . $row['ID'] . '" value="' . $qty . '"> </td></tr>';
                            }
                        }
                        echo '<tr></tr><tr></tr><tr></tr><tr><td></td><td></td><td></td><td><input type="submit" value="Aggiungi al carrello"></td></form></tr>';
                    }
                ?>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <a href="conferma.php"> <input type="submit" name="conferma" value="Conferma"> </a>
                    </td>
                    <td>
                        <a href="acquisto.php?reset=1"> <input type="reset" name="cancella" value="Cancella"> </a>
                    </td>
                </tr>
            </table>
        </main>

        <footer>
            &copy; 2023 - Eventlandia - Created by Mario Cappello - File: <?php echo basename($_SERVER['PHP_SELF']); ?>
        </footer>
    </body>
</html>