<?php
session_start();

session_destroy();
header("Location: /PFE/carteAnimees/index.php");
exit();