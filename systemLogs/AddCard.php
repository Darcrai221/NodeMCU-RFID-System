<!-- 
  NodeMCU RFID System
  Marc Antony Martínez Mejía
  Marlene Medellin González
-->
<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Añadir nuevo usuario</title>

  <!-- Referencias y Scripts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/lux/bootstrap.min.css" integrity="sha384-9+PGKSqjRdkeAU7Eu4nkJU8RFaH8ace8HGXnkiKMP9I9Te0GJ4/km3L1Z8tXigpG" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">

  <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous">
  </script>
  <script src="js/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      setInterval(function() {
        $.ajax({
          url: "add-users.php"
        }).done(function(data) {
          $('#User').html(data);
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
    <div class="row">
      <div class="card mb-3">
        <h3 class="card-header">Registrar tarjeta</h3>
        <div class="form-group">
          <div class="col-lg-12">
            <form action="user_insert.php" method="POST">
              <div class="form-group">
                <label class="form-label mt-4">Name: </label>
                <input class="form-control" type="text" placeholder="User Name" name="Uname" required>
              </div>
              <div class="form-group">
                <label class="form-label mt-4">Number: </label>
                <input class="form-control" type="number" placeholder="Serial Number" name="Number">
              </div>
              <div class="form-group">
                <legend class="mt-4">Gender</legend>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" value="Male" required>
                  <label class="form-check-label">
                    Male
                  </label>
                  <br>
                  <input class="form-check-input" type="radio" name="gender" value="Female" required>
                  <label class="form-check-label">
                    Female
                  </label>
                </div>
              </div>
              <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Add" name="login" id="lo">
                <input class="btn btn-outline-primary" type="submit" value="Update" name="update" id="up">
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <h3 class="card-header">Opciones</h3>
        <div class="col-lg-12">
          <form method="POST" action="user_insert.php">
            <br>
            <input class="form-control" type="text" name="CardID" placeholder="Card ID">
            <br>
            <button class="btn btn-primary" type="submit" name="set" title="Select">Seleccionar</button>
            <button class="btn btn-outline-primary" type="submit" name="del" title="Remove">Eliminar</button>
          </form>
        </div>
      </div>

      <div class="alert alert-dismissible alert-secondary">
        <?php
        if (isset($_GET['error'])) {
          if ($_GET['error'] == "SQL_Error") {
            echo '<label style="color:red">¡Error en SQL !</label>';
          } else if ($_GET['error'] == "Nu_Exist") {
            echo '<label style="color:red">¡El numero ya ha sido registrado!</label>';
          } else if ($_GET['error'] == "No_SelID") {
            echo '<label style="color:red">¡No se ha seleccionado ninguna tarjeta!</label>';
          } else if ($_GET['error'] == "No_ExID") {
            echo '<label style="color:red">¡Esta tarjeta no existe!</label>';
          }
        } else if (isset($_GET['success'])) {
          if ($_GET['success'] == "registerd") {
            echo '<label style="color:green;">La tarjeta ha sido registrada correctamente</label>';
          } else if ($_GET['success'] == "Updated") {
            echo '<label style="color:green;">La tarjeta ha sido actualizada correctamente</label>';
          } else if ($_GET['success'] == "deleted") {
            echo '<label style="color:green;">La tarjeta ha sido eliminada correctamente</label>';
          } else if ($_GET['success'] == "Selected") {
            echo '<label style="color:green;">La tarjeta ha sido seleccionada correctamente</label>';
          }
        }
        ?>
      </div>
    </div>

    <!-- Table -->
    <div id="User"></div>
  </section>
</body>

</html>