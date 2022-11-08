<!-- 
  NodeMCU RFID System
  Marc Antony Martínez Mejía
  Marlene Medellin González
-->

<?php
session_start();
//Connect to database
require 'connectDB.php';
//**********************************************************************************************

//Get current date and time
date_default_timezone_set('America/Mexico_City');
$d = date("Y-m-d");

$Tarrive = mktime(01, 30, 00);
$TimeArrive = date("H:i:sa", $Tarrive);
//**********************************************************************************************   
$Tleft = mktime(02, 30, 00);
$Timeleft = date("H:i:sa", $Tleft);

if (!empty($_POST['seldate'])) {
  $seldate = $_POST['date'];
} else {
  $seldate = $d;
}

$_SESSION["exportdata"] = $seldate;
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registros</title>

  <!-- Referencias y Scripts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/lux/bootstrap.min.css" integrity="sha384-9+PGKSqjRdkeAU7Eu4nkJU8RFaH8ace8HGXnkiKMP9I9Te0GJ4/km3L1Z8tXigpG" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
  <script src="js/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      setInterval(function() {
        $.ajax({
          url: "load-users.php"
        }).done(function(data) {
          $('#cards').html(data);
        });
      }, 3000);
    });
  </script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">RFID Nodemcu</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="AddCard.php">Registrar usuarios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="view.php">Registros</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <section>
    <h1>Registros de usuarios</h1>

    <div class="row">
      <div class="col-lg-7">
        <p>
          Hora de entrada: <?php echo $TimeArrive ?><br>
          Hora de salida: <?php echo $Timeleft ?>
        </p>
      </div>

      <div class="col-lg-2">
        <button type="button" class="btn btn-primary" onclick="location.href='AddCard.php'">Añadir nuevo
          usuario</button>
      </div>

      <div class="col-lg-1">
        <form method="post" action="export.php">
          <input type="submit" name="export" class="btn btn-primary disabled" value="Exportar datos a Excel"></button>
        </form>
        <!--
        <form method="post" action="export.php">
          <input type="submit" name="export" class="export" value="Export to Excel"/>
        </form>
        -->
      </div>
    </div>

    <!--
    <p>Selecciona la fecha:
    <form method="POST" action="">
      <input type="date" name="date"><br>
      <input type="submit" name="seldate" value="Select Date" id="inp">
    </form>
    -->

    <div class="card mb-3">
      <h3 class="card-header">Selecciona la fecha:</h3>
      <div class="card-body">
        <form method="POST" action="">
          <input type="date" name="date">
          <input type="submit" name="seldate" class="btn btn btn-link" value="Seleccionar fecha" id="inp">
        </form>
      </div>
    </div>

    <!-- Table -->
    <div id="cards" class="cards"> </div>
  </section>
</body>

</html>