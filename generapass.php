<?php
$plain_password = 'Encarrera$25';
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

echo $hashed_password;
?>
