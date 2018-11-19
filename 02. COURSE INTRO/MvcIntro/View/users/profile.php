<?php
/** @var Model\UserProfileViewModel $model */
$fullName = $model->getFirstName() . ' ' . $model->getLastName();
?>

<h1>Hello, <?= $fullName ?></h1>