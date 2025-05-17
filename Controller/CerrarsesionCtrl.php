<?php
session_start();
session_destroy();
header("Location: ../View/index.php");  // O a la vista que quieras
exit;