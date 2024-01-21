<!DOCTYPE html>
<html>
    <head>
        <title>Conferma</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>EventLandia</h1>
        </header>
        <?php
            session_start();
        ?>

        <ul class="navbar">
            <li class="nav-item"><a href="home.php">Home</a></li>
            <li class="nav-item"><a href="info.php">Info</a></li>
            <li class="nav-item"><a href="acquisto.php">Compra</a></li>
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
            <?php
                $total = 0;
                if (isset($_SESSION['cart'])) {
                    echo "<table> <tr> <th> Evento </th> <th> Quantità </th> <th> Prezzo totale </th> </tr>";

                    foreach ($_SESSION['cart'] as &$item) {
                        $id = $item['id'];
                        $qty = $item['qty'];

                        $query = "SELECT * FROM eventi WHERE ID='$id'";
                        $conn = mysqli_connect("localhost","uNormal","posso_leggere?","biglietteria");

                        if (!$conn) {
                            echo "Cannot connect to the database.";
                        }

                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_assoc($result);

                            $name = $row['Nome'];
                            $price = $row['Costo'] * $qty;
                        }

                        echo "<tr><td>". $name ."</td><td>". $qty ."</td><td>" . $price . "</td></tr>";
                        
                        $total += $price;
                    }

                    echo "</table>";
                } else {
                    echo "Carrello vuoto";
                }

                echo '<br><br><br><a href="acquisto.php?reset=1"> <input type="reset" name="reset" value="Reset"> </a>';
                echo '<a href="acquisto.php"> <input type="submit" name="button" value="Indietro"> </a>';

                if ($total > $_SESSION['credito']) {
                    echo '<br><br><br><h2> Attenzione non hai abbastanza credito per effettuare l\'acquisto. Premi il pulsante INDIETRO o il pulsante RESET per continuare. </h2>';
                } else {
                    $_SESSION['totale'] = $total;
                    echo '<a href="fine.php"> <input type="submit" value="Concludis"> </a>';
                }
            ?>
        </main>
 
        <footer>
            &copy; 2023 - Eventlandia - Created by Mario Cappello - File: <?php echo basename($_SERVER['PHP_SELF']); ?>
        </footer>
    </body>
</html>