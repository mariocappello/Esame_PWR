<!DOCTYPE html>
<html>
<head>
    <title>Gestione</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Eventlandia</h1>
    </header>

    <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            foreach ($_POST as $key => $value) {
                if (strpos($key, "date_event_") === 0) {
                    $id = substr($key, 11);
                    $date = $value;

                    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
                        $query = "UPDATE eventi SET Data='$date' WHERE ID='$id'";
                        $db_conn = mysqli_connect("localhost", "uPower", "SuperPippo!!!", "biglietteria");
                
                        if (!$db_conn) {
                            die('Connessione fallita al database: ' . mysqli_error($db_conn));
                        }
                
                        $sql = mysqli_query($db_conn, $query);
                    } else {
                        echo 'Wrong date format. Date must be in format yyyy-mm-dd';
                        exit;
                    }
                } else if (strpos($key, "qty_event_") === 0) {
                    $id = substr($key, 10);
                    $qty = $value;

                    $query = "UPDATE eventi SET Quantità='$qty' WHERE ID='$id'";
                    $db_conn = mysqli_connect("localhost", "uPower", "SuperPippo!!!", "biglietteria");
                
                    if (!$db_conn) {
                        die('Connessione fallita al database: ' . mysqli_error($db_conn));
                    }
            
                    $sql = mysqli_query($db_conn, $query);
                } else if (strpos($key, "cost_event_") === 0) {
                    $id = substr($key, 11);
                    $cost = $value;

                    $query = "UPDATE eventi SET Costo='$cost' WHERE ID='$id'";
                    $db_conn = mysqli_connect("localhost", "uPower", "SuperPippo!!!", "biglietteria");
                
                    if (!$db_conn) {
                        die('Connessione fallita al database: ' . mysqli_error($db_conn));
                    }
            
                    $sql = mysqli_query($db_conn, $query);
                } else if (strpos($key, "new_event") === 0) {
                    $date = $_POST['new_date'];
                    $qty = $_POST['new_qty'];
                    $name = $_POST['new_event'];
                    $cost = $_POST['new_cost'];

                    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
                        echo 'Wrong date format. Date must be in format yyyy-mm-dd. You will be now redirect to the gestione page.';
                        sleep(5);
                        header('location: gestione.php');
                        exit;
                    }        

                    $query = "INSERT INTO eventi (Nome, Data, Quantità, Costo) VALUES('$name', '$date', '$qty', '$cost')";
                    $db_conn = mysqli_connect("localhost", "uPower", "SuperPippo!!!", "biglietteria");
                
                    if (!$db_conn) {
                        die('Connessione fallita al database: ' . mysqli_error($db_conn));
                    }
            
                    $sql = mysqli_query($db_conn, $query);
                }
            }
        }
    ?>
    
    <ul class="navbar">
        <li class="nav-item"><a href="home.php">Home</a></li>
        <li class="nav-item"><a href="info.php">Info</a></li>
        <li class="nav-item"><a href="acquisto.php">Compra</a></li>
        <li class="nav-item"><a href="#">Gestisci</a></li>
        <?php
            if (!isset($_SESSION['organizzatore']) || $_SESSION['organizzatore'] == 0) {
                header('Location: home.php');
            }

            if (isset($_SESSION['username'])) {               
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
        <h2> Eventi </h2>
            <table>
                <tr> 
                    <th> Evento </th>
                    <th> Data </th>
                    <th> Biglietti </th>
                    <th> Prezzo  </th>
                    <th> Modifica </th>
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
                    echo '<tr><form method="post">';
                    echo '<td>' . $row['Nome'] .'</td><td>';
                    echo '<input type="text" name="date_event_' . $row['ID'] . '" value="' . $row['Data'] . '"></td><td>';
                    echo '<input type="number" name="qty_event_' . $row['ID'] . '" value="' . $row['Quantità'] . '"></td><td>';
                    echo '<input type="number" name="cost_event_' . $row['ID'] . '" value="' . $row['Costo'] . '"</td><td>';
                    echo '<input type="submit" value="Conferma modifiche"></td></form></tr>';
                }
            }
        ?>
        </table>

        <h2> Crea nuovo evento </h2>
        <form method="post">
            <label for="new_event"> Nome evento </label>
            <input type="text" name="new_event" value="Nome evento"> <br> <br>
            <label for="new_date"> Data evento </label>
            <input type="text" name="new_date" value="aaaa-mm-gg"> <br> <br>
            <label for="new_qty"> Quantità biglietti disponibili </label>
            <input type="number" name="new_qty" value="0"> <br> <br>
            <label for="new_cost"> Costo biglietto </label>
            <input type="number" name="new_cost" value="0"> <br> <br>
            <br>
            <br>
            <input type="reset" name="Resetta campi">
            <input type="submit" name="Crea evento">
        </form>
    </main>

    <footer>
        &copy; 2023 - Eventlandia - Created by Mario Cappello - File: <?php echo basename($_SERVER['PHP_SELF']); ?>
    </footer>
</body>
</html>
