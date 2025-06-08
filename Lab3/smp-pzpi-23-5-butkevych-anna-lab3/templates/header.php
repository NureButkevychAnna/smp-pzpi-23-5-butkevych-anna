<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Web-магазин</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<div class="container">
    <header class="main-header">
        <h1><a href="index.php">Web-магазин</a></h1>
        <nav>
            <?php
            $currentPage = $_GET['page'] ?? 'home';
            ?>
            <a href="index.php?page=home" class="button <?= ($currentPage === 'home') ? 'button-primary' : '' ?>">Home</a>
            <a href="index.php?page=products" class="button <?= ($currentPage === 'products') ? 'button-primary' : '' ?>">Products</a>
            <a href="index.php?page=cart" class="button <?= ($currentPage === 'cart') ? 'button-primary' : '' ?>">Cart</a>
        </nav>
    </header>
    <main>