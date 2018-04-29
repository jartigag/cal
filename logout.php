<?php
session_start();
session_unset();
session_destroy();
header("Location: /cal/"); // IMPORTANTE: se ha usado /cal/ como parte de la url
?>