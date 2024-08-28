<?php
// La contraseña en texto plano
$plain_password = 'Klikowski$1';

// El hash de la contraseña almacenado en la base de datos
$hashed_password = '$2y$10$HRZJvTTN.4S4/p4UuNsb9OqV1YquN02FTdFmCvTTddUu1ZHO3xOkW';

// Verificar si la contraseña en texto plano coincide con el hash
if (password_verify($plain_password, $hashed_password)) {
    echo "La contraseña es correcta.";
} else {
    echo "La contraseña es incorrecta.";
}
?>
