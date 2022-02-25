<?php

/**
 * Commencez par importer le fichier sql live.sql via PHPMyAdmin.
 * 1. Sélectionnez tous les utilisateurs.
 * 2. Sélectionnez tous les articles.
 * 3. Sélectionnez tous les utilisateurs qui parlent de poterie dans un article.
 * 4. Sélectionnez tous les utilisateurs ayant au moins écrit deux articles.
 * 5. Sélectionnez l'utilisateur Jane uniquement s'il elle a écris un article ( le résultat devrait être vide ! ).
 *
 * ( PS: Sélectionnez, mais affichez le résultat à chaque fois ! ).
 */

try {
    $server = 'localhost';
    $db = 'exo_206';
    $user = 'root';
    $pswd = '';

    $bdd = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $user, $pswd);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    //1
    $stm = $bdd->prepare("
        SELECT * FROM user
    ");

    $stm->execute();
    echo '<pre>';
    print_r($stm->fetchAll());
    echo "</pre><br>";

    //2
    $stm = $bdd->prepare("
        SELECT * FROM article
    ");

    $stm->execute();
    echo '<pre>';
    print_r($stm->fetchAll());
    echo "</pre><br>";

    //3
    $stm = $bdd->prepare("
        SELECT username FROM user WHERE id = (SELECT user_fk FROM article WHERE contenu LIKE '%poterie%')
    ");

    if ($stm->execute()) {
        foreach ($stm->fetchAll() as $item) {
            echo $item['username'] . " parle de poterie dans un article <br>";
        }
    }

    //4
    $stm = $bdd->prepare("
        SELECT username FROM user WHERE id = (SELECT count(user_fk) FROM article)
    ");

    if ($stm->execute()) {
        foreach ($stm->fetchAll() as $item) {
            echo $item['username'] . " ont écrit deux articles <br>";
        }
    }

    //5


}
catch (PDOException $e) {
    echo $e->getMessage();
}