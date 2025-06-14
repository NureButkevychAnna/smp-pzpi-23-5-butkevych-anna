<?php

$productsFile = __DIR__ . "/products.json";
if (!file_exists($productsFile)) {
    echo "Файл з продуктами не знайдено: $productsFile\n";
    exit(1);
}

$jsonData = file_get_contents($productsFile);
$products = json_decode($jsonData, true);
if (!is_array($products)) {
    echo "Помилка: Неможливо розпарсити JSON з продуктами.\n";
    exit(1);
}

$cart = [];
$user = ["name" => "", "age" => 0];

function printMenu() {
    echo "\n################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    echo "Введіть команду: ";
}

function selectProducts(&$cart, $products) {
    while (true) {
        echo "\n№  НАЗВА                      ЦІНА\n";
        foreach ($products as $num => $item) {
            $name = $item["name"];
            $price = $item["price"];
            $pad = 26 - mb_strlen($name, 'UTF-8');
            $padded_name = $name . str_repeat(' ', max($pad, 0));

            printf("%-2d %s %5d\n", $num, $padded_name, $price);
        }

        echo "   -----------\n0  ПОВЕРНУТИСЯ\n";
        echo "Виберіть товар: ";
        $choice = trim(fgets(STDIN));

        if (!is_numeric($choice)) continue;

        $choice = intval($choice);

        if ($choice === 0) break;

        if (!isset($products[$choice])) {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
            continue;
        }

        echo "Вибрано: {$products[$choice]['name']}\n";
        echo "Введіть кількість, штук: ";
        $qty = trim(fgets(STDIN));

        if (!is_numeric($qty) || intval($qty) < 0 || intval($qty) > 99) {
            echo "ПОМИЛКА! Невірна кількість.\n";
            continue;
        }

        $qty = intval($qty);
        if ($qty === 0) {
            unset($cart[$choice]);
            echo "ВИДАЛЯЮ З КОШИКА\n";
        } else {
            $cart[$choice] = $qty;
        }

        if (count($cart) > 0) {
            echo "У КОШИКУ:\nНАЗВА        КІЛЬКІСТЬ\n";
            foreach ($cart as $id => $q) {
                echo "{$products[$id]['name']}  $q\n";
            }
        } else {
            echo "КОШИК ПОРОЖНІЙ\n";
        }
    }
}

function showReceipt($cart, $products) {
    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }

    echo "№  НАЗВА                 ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
    $i = 1;
    $total = 0;
    foreach ($cart as $id => $qty) {
        $name = $products[$id]['name'];
        $price = $products[$id]['price'];
        $cost = $price * $qty;
        $total += $cost;
        printf("%-2d %-25s %6d %8d %9d\n", $i++, $name, $price, $qty, $cost);
    }
    echo "РАЗОМ ДО CПЛАТИ: $total\n";
}

function setupProfile(&$user) {
    while (true) {
        echo "Ваше імʼя: ";
        $name = trim(fgets(STDIN));
        if (!preg_match('/[a-zA-Zа-яА-ЯіІїЇєЄґҐ]/u', $name)) {
            echo "ПОМИЛКА: Імʼя не може бути порожнім і повинно містити хоча б одну літеру.\n";
            continue;
        }
        break;
    }

    while (true) {
        echo "Ваш вік: ";
        $age = trim(fgets(STDIN));
        if (!is_numeric($age) || $age < 7 || $age > 150) {
            echo "ПОМИЛКА: Вік повинен бути від 7 до 150.\n";
            continue;
        }
        break;
    }

    $user['name'] = $name;
    $user['age'] = intval($age);
    echo "Профіль збережено!\n";
}

while (true) {
    printMenu();
    $cmd = trim(fgets(STDIN));

    switch ($cmd) {
        case "1":
            selectProducts($cart, $products);
            break;
        case "2":
            showReceipt($cart, $products);
            break;
        case "3":
            setupProfile($user);
            break;
        case "0":
            echo "До побачення!\n";
            exit(0);
        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
    }
}
