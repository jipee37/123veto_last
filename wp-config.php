<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', '123veto');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'jocelyn@357');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');
define('FS_METHOD', 'direct');
/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'KRy,C2=uy#KTINEU(,eGo04#N(=rbXsb^OkA0Z?FwH>IYX,J _r%VXn}xkF5xCds');
define('SECURE_AUTH_KEY',  'tGJT[)Cpk-/v/n89{3M#GNP<eii1{)hM:lKGa#t#tXECVM1R_N(86/lA= Jsi^0*');
define('LOGGED_IN_KEY',    '1+t]2V0X`Z`lNEJ9aRxJSS1Qa{25i:06*Y0q;#D.AptSO7e|.hm#32lmQCkgPvX>');
define('NONCE_KEY',        '&<19?-_2bq9w48&]Dd4N5ALpbRi>wSG^oad|k!_Q*P+|LOkBB?#/4fNZJT%GbI^.');
define('AUTH_SALT',        'K9^jwQM?Ep5{q2w;3$Va|<ZTo-P&l=!D][X5kD3%)/aMDa0eYtqbcA=<=f^T8@UG');
define('SECURE_AUTH_SALT', '%7/pWq|kZd(>v1FyVV^jg]l!MxS-S +USj-}=JQoB1GThz_~>=Sjff~ZY^8&MDo|');
define('LOGGED_IN_SALT',   'HltRt{db:>]c~2Y(9upErd{i?=5{.}l&Fqn6udx{K3A07a+x/(g#04kB&*qK!Eo*');
define('NONCE_SALT',       'Ql{kY64LPyjwG5%8%q:A/BdMbiW(oTFpcZv2Q`IGwpJ1+acFkQ45hRD@7DE>bHeQ');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d'information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress 
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
