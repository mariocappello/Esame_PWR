<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Eventlandia</h1>
    </header>
    
    <ul class="navbar">
        <li class="nav-item"><a href="#">Home</a></li>
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
	<p> Il sito web permette, attraverso la corretta autenticazione dell'utente con username e password, la compravendita di biglietti o la gestione di uno o più eventi da parte dell'organizzatore.
Per un utente compratore è possibile acquistare uno o più biglietti, per uno o più eventi disponibili per cui siano in vendita biglietti. Il sistema non permette ad un utente non autenticato di acquistare biglietti.
Per un utente organizzatore è possibile inserire un nuovo evento o modificarne uno già presente. Allo stesso modo sistema non permette ad un utente organizzatore non autenticato di creare o modificare un evento.
Per un utente correttamente autenticato è possibile inoltre visualizzare il nome utente e il credito disponibile, oltre che uscire dal sistema tramite la voce LOGOUT. </p>
    <footer>
        &copy; 2023 - Eventlandia - Created by Mario Cappello - File: <?php echo basename($_SERVER['PHP_SELF']); ?>
    </footer>
</body>
</html>
