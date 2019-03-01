<?php
include('config/config.php');
include('lib/bdd.lib.php');
/**
 * 1. Récupérer le code postal dans la chaine de requête
 * 2. Connection au serveur 
 * 3. Préparation de ma requête SQL 
 * 4. Execution de la requête SQL (avec bindage du code postal reçu dans la requête)
 * 5. Récupération du Jeu d'enregistrement
 * 6. Affichage du jeu d'enregistrement (var_dump);
 */


/** HOlala on veut tester d'abord
 * 
 * 1. Connexion
 * 2. Requête simple avec un code postal en dur
 * 3. Execute et on affiche 
 */

try
{
    /** On envoi une exception si l'id n'est pas pasé dans la chaine de requête
     * Le reste des ligne du bloc try ne sera pas executé
     * On va directement au bloc catch
     */
    if(!array_key_exists('cp',$_GET))
        throw new Exception('Merci de fournir un code postal');

    $codePostal = $_GET['cp'];

    /** 1 : connexion au serveur de BDD - SGBDR */
    $dbh = connexion();

    /**2 : Prépare ma requête SQL */
    $sth = $dbh->prepare('SELECT * FROM villes WHERE ville_code_postal LIKE :codePostal OR ville_code_postal LIKE :codePostal2');

    /** 3 : executer la requête et bindage en une ligne
     */
    $sth->bindValue(':codePostal',$codePostal.'%',PDO::PARAM_STR);
    $sth->bindValue(':codePostal2','%-'.$codePostal.'%',PDO::PARAM_STR);
    $sth->execute();

    /** 4 : recupérer les résultats 
    */
    $villes = $sth->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($villes);
}
catch(PDOException $e)
{
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    echo 'Une erreur de connexion a eu lieu :'.$e->getMessage();
}
catch(Exception $e)
{
    //Si une exception est envoyée
    echo 'Erreur dans la page :'.$e->getMessage();
}