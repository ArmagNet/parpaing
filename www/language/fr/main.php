<?php /*
	Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of Parpaing.

    Parpaing is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Parpaing is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Parpaing.  If not, see <http://www.gnu.org/licenses/>.
*/

$lang["date_format"] = "d/m/Y";
$lang["time_format"] = "H:i";
$lang["datetime_format"] = "le {date} à {time}";

$lang["common_validate"] = "Valider";
$lang["common_delete"] = "Supprimer";
$lang["common_activate"] = "Activer";
$lang["common_add"] = "Ajouter";
$lang["common_modify"] = "Modifier";
$lang["common_reset"] = "Reset";
$lang["common_connect"] = "Connecter";

$lang["language_fr"] = "Français";
$lang["language_en"] = "Anglais";
$lang["language_de"] = "Allemand";

$lang["parpaing_title"] = "Parpaing - Un mini routeur VPN";

$lang["menu_language"] = "Langue : {language}";
$lang["menu_status"] = "Statut";
$lang["menu_vpn"] = "VPN";
$lang["menu_tv"] = "TV";
$lang["menu_telephone"] = "Téléphone";
$lang["menu_wifi"] = "Wifi";
$lang["menu_upgrader"] = "Mise à jour";

$lang["menu_mypreferences"] = "Mes préférences";
$lang["menu_myaccounts"] = "Mes comptes";
$lang["menu_logout"] = "Se déconnecter";

$lang["upgrader_actualVersion"] = "Version actuelle";
$lang["upgrader_newVersion"] = "Version disponible";
$lang["upgrader_version_version"] = "Version";
$lang["upgrader_version_description"] = "Description";
$lang["upgrader_startUpgrade_button"] = "Faire la mise à jour";
$lang["upgrader_rebootInterface_button"] = "Relancer l'interface";

$lang["login_title"] = "Identifiez vous";
$lang["login_loginInput"] = "Identifiant";
$lang["login_passwordInput"] = "Mot de passe";
$lang["login_button"] = "Me connecter";
$lang["login_rememberMe"] = "Se souvenir de moi";
$lang["register_link"] = "ou m'enregistrer";
$lang["forgotten_link"] = "j'ai oublié mon mot de passe";

$lang["breadcrumb_index"] = "Accueil";
$lang["breadcrumb_vpn"] = "VPN";
$lang["breadcrumb_tv"] = "TV";
$lang["breadcrumb_telephone"] = "Telephone";
$lang["breadcrumb_wifi"] = "Wifi";
$lang["breadcrumb_upgrader"] = "Mise à jour";
$lang["breadcrumb_about"] = "À Propos";

$lang["index_guide"] = "Parpaing est l'interface de gestion simple de votre mini routeur VPN.";
$lang["index_accounts"] = "Comptes";
$lang["index_tweetPlaceholder"] = "tweet...";
$lang["index_tweetButton"] = "Tweeter";
$lang["index_options_mediaInput"] = "Media";
$lang["index_options_cronDateInput"] = "Départ différé";
$lang["index_options_cronDatePlaceholder"] = "aaaa-mm-jj hh:mm";
$lang["index_options_cronDateGuide"] = "Laisser vide si départ juste après validation";
$lang["index_options_validationDurationInput"] = "Durée de validation maximale";
// $lang["index_options_validationDurationPlaceholder"] = "yyyy-mm-dd hh:mm";
// $lang["index_options_validationDurationGuide"] = "Laisser vide si départ juste après validation";
$lang["anonymous_form_nicknameInput"] = "Surnom";
$lang["anonymous_form_mailInput"] = "Adresse mail (pour suivi)";
$lang["anonymous_form_passwordInput"] = "Mot de passe";
$lang["anonymous_form_iamabot"] = "Je suis un robot et je ne sais pas décocher une case";
$lang["anonymous_form_legend"] = "Informations";

$lang["add_tweet_mail_subject"] = "[OTB] Validation d'un tweet demandé";
$lang["add_tweet_mail_content"] = "Bonjour {login},

Vous êtes dans une liste de validateurs du compte {account}, et, un tweet vous attend sur Parpaing dont voici le contenu :

{tweet}

Vous pouvez directement valider ce tweet en cliquant sur le lien ci-dessous :
{validationLink}

L'équipe #Parpaing";

$lang["do_validation_error"] = "Votre validation a échoué (déjà effectuée, tweet déjà envoyé ou effacé)";
$lang["do_validation_ok"] = "Votre validation a bien été prise en compte";

$lang["mypreferences_guide"] = "Changer mes préférences.";
$lang["mypreferences_form_legend"] = "Configuration de vos accès";
$lang["mypreferences_form_passwordInput"] = "Mot de passe";
$lang["mypreferences_form_passwordPlaceholder"] = "le mot de passe de connexion";
$lang["mypreferences_form_languageInput"] = "Langage";
$lang["mypreferences_form_notificationInput"] = "Notification pour validation";
$lang["mypreferences_form_notification_none"] = "Aucune";
$lang["mypreferences_form_notification_mail"] = "Par mail";
$lang["mypreferences_form_notification_simpledm"] = "Par simple DM";
$lang["mypreferences_form_notification_dm"] = "DM multiple";
$lang["mypreferences_validation_mail_empty"] = "Le champ mail ne peut être vide";
$lang["mypreferences_validation_mail_not_valid"] = "Cette adresse mail n'est pas une adresse valide";
$lang["mypreferences_validation_mail_already_taken"] = "Cette adresse mail est déjà prise";
$lang["mypreferences_form_mailInput"] = "Adresse mail";
$lang["mypreferences_save"] = "Sauver mes préférences";

$lang["myaccounts_guide"] = "Paramétrer mes comptes.";
$lang["myaccounts_newaccount_form_legend"] = "Configuration d'un nouveau compte";
$lang["myaccounts_existingaccount_form_legend"] = "Configuration du compte <em>{account}</em>";
$lang["myaccounts_account_form_nameInput"] = "Nom du compte";
$lang["myaccounts_account_form_anonymousPermitted"] = "Proposition anonyme de tweet authorisée";
$lang["myaccounts_account_form_anonymousPasswordInput"] = "Mot de passe pour les anonymes";
$lang["myaccounts_account_form_validationScoreInput"] = "Score de validation d'un tweet";
$lang["myaccounts_twitter_form_legend"] = "Configuration Twitter";
$lang["myaccounts_twitter_form_apiKeyInput"] = "API Key";
$lang["myaccounts_twitter_form_apiSecretInput"] = "API Secret";
$lang["myaccounts_twitter_form_accessTokenInput"] = "Access Token";
$lang["myaccounts_twitter_form_accessTokenSecretInput"] = "Access Token Secret";
$lang["myaccounts_administrators_form_legend"] = "Gestion des administrateurs";
$lang["myaccounts_administrators_form_addUserInput"] = "Utilisateur";
$lang["myaccounts_validators_form_legend"] = "Gestion des validateurs";
$lang["myaccounts_validators_form_groupNameInput"] = "Nom du groupe";
$lang["myaccounts_validators_form_groupScoreInput"] = "Score";
$lang["myaccounts_validators_form_addUserInput"] = "Utilisateur";
$lang["myaccounts_validators_form_deleteGroupInput"] = "Supprimer groupe";
$lang["myaccounts_validators_form_addGroupInput"] = "Ajouter groupe";
$lang["myaccount_button_testTwitter"] = "Tester";
$lang["myaccount_add"] = "Ajouter ce compte";
$lang["myaccount_save"] = "Sauver les paramètres";

$lang["myrights_guide"] = "Une revue de vos droits.";
$lang["myrights_scores_legend"] = "Mes validations possibles";
$lang["myrights_scores_no_score"] = "Vous n'avez aucun pouvoir de validation";
$lang["myrights_scores_my_score"] = "Votre pouvoir de validation";
$lang["myrights_scores_validation_score"] = "Les points requis pour valider";
$lang["myrights_administration_legend"] = "Mes comptes administrés";
$lang["myrights_scores_no_adminstation"] = "Vous n'avez aucun droit d'administration";

$lang["mypage_guide"] = "Ceci est une page compilant vos statistiques";
$lang["mypage_tweets_legend"] = "Mes tweets";
$lang["mypage_validations_legend"] = "Mes validations";
$lang["mypage_scores_legend"] = "Mes scores";
$lang["mypage_tweet_and_validations_chart_legend"] = "Mes tweets et validations dans le temps";
$lang["mypage_tweet_and_validations_chart_axisY"] = "Quantité";
$lang["mypage_score_chart_axisY"] = "Score";
$lang["mypage_tweet_and_validations_chart_axisX"] = "Date";
$lang["mypage_tweet_and_validations_chart_formatDate"] = "DD/MM/YYYY";
$lang["mypage_tweet_and_validations_chart_jsFormatDate"] = "(date.getDate() < 10 ? '0' : '') + date.getDate() + '/' + (date.getMonth() < 11 ? '0' : '') + (date.getMonth() + 1) + '/' + date.getFullYear()";

$lang["property_tweet"] = "Tweet";
$lang["property_author"] = "Auteur";
$lang["property_date"] = "Date";
$lang["property_validators"] = "Validateurs";
$lang["property_validation"] = "Validation";
$lang["property_actions"] = "Actions";

$lang["register_guide"] = "Bienvenue sur la page d'enregistrement d'Parpaing";
$lang["register_form_legend"] = "Configuration de votre accès";
$lang["register_form_loginInput"] = "Identifiant";
$lang["register_form_loginHelp"] = "Utilisez de préférence votre identifiant Twitter si vous voulez recevoir des notifications sur Twitter";
$lang["register_form_mailInput"] = "Adresse mail";
$lang["register_form_passwordInput"] = "Mot de passe";
$lang["register_form_passwordHelp"] = "Votre mot de passe ne doit pas forcement contenir de caractères bizarres, mais doit de préférence être long et mémorisable";
$lang["register_form_confirmationInput"] = "Confirmation du mot de passe";
$lang["register_form_languageInput"] = "Langage";
$lang["register_form_iamabot"] = "Je suis un robot et je ne sais pas décocher une case";
$lang["register_form_notificationInput"] = "Notification pour validation";
$lang["register_form_notification_none"] = "Aucune";
$lang["register_form_notification_mail"] = "Par mail";
$lang["register_form_notification_simpledm"] = "Par simple DM";
$lang["register_form_notification_dm"] = "DM multiple";
$lang["register_success_title"] = "Enregistrement réussi";
$lang["register_success_information"] = "Votre enregistrement a réussi.
<br>Vous allez recevoir un mail avec un lien à cliquer permettant l'activation de votre compte.";
$lang["register_mail_subject"] = "[OTB] Mail d'enregistrement";
$lang["register_mail_content"] = "Bonjour {login},

Il semblerait que vous vous soyez enregistré sur Parpaing. Pour confirmer votre enregistrement, veuillez cliquer sur le lien ci-dessous :
{activationUrl}

L'équipe #Parpaing";
$lang["register_save"] = "S'enregistrer";
$lang["register_validation_user_empty"] = "Le champ utilisateur ne peut être vide";
$lang["register_validation_user_already_taken"] = "Cet utilisateur est déjà pris";
$lang["register_validation_mail_empty"] = "Le champ mail ne peut être vide";
$lang["register_validation_mail_not_valid"] = "Cette adresse mail n'est pas une adresse valide";
$lang["register_validation_mail_already_taken"] = "Cette adresse mail est déjà prise";
$lang["register_validation_password_empty"] = "Le champ mot de passe ne peut être vide";

$lang["activation_guide"] = "Bienvenue sur l'écran d'activation de votre compte";
$lang["activation_title"] = "Statut de votre activation";
$lang["activation_information_success"] = "L'activation de votre compte utilisateur a réussi. Vous pouvez maintenant vous <a id=\"connectButton\" href=\"#\">identifier</a>.";
$lang["activation_information_danger"] = "L'activation de votre compte utilisateur a échoué.";

$lang["forgotten_guide"] = "Vous avez oublié votre mot de passe, bienvenue sur la page qui vour permettra de récuperer un accès";
$lang["forgotten_form_legend"] = "Récupération d'accès";
$lang["forgotten_form_mailInput"] = "Adresse mail";
$lang["forgotten_save"] = "Envoyez moi un mail !";
$lang["forgotten_success_title"] = "Récupération en cours";
$lang["forgotten_success_information"] = "Un mail vous a été envoyé.<br>Ce mail contient un nouveau mot de passe. Veillez à le changer aussitôt que possible.";
$lang["forgotten_mail_subject"] = "[OTB] J'ai oublié mon mot de passe";
$lang["forgotten_mail_content"] = "Bonjour,

Il semblerait que vous ayez oublié votre mot de passe sur Parpaing. Votre nouveau mot de passe est {password} .
Veuillez le changer aussitôt que vous serez connecté.

L'équipe #Parpaing";

$lang["defaultPasswordAlert"] = "Mot de passe par défaut, à changer";
$lang["notSameNewPasswordAlert"] = "Les nouveaux mots de passe indiqués ne sont pas identiques";
$lang["badPasswordAlert"] = "Mauvais mot de passe";

$lang["install_guide"] = "Bienvenue sur la page d'installation d'Parpaing.";
$lang["install_tabs_database"] = "Base de données";
$lang["install_tabs_mail"] = "Mail";
$lang["install_tabs_application"] = "Application";
$lang["install_tabs_final"] = "Finalisation";
$lang["install_tabs_license"] = "Licence";
$lang["install_database_form_legend"] = "Configuration des accès base de données";
$lang["install_database_hostInput"] = "Hôte";
$lang["install_database_hostPlaceholder"] = "l'adresse du serveur de base de données";
$lang["install_database_portInput"] = "Port";
$lang["install_database_portPlaceholder"] = "le port du serveur de base de données";
$lang["install_database_loginInput"] = "Identifiant";
$lang["install_database_loginPlaceholder"] = "l'identifiant de connexion";
$lang["install_database_loginHelp"] = "On évite l'utilisateur <em>root</em>";
$lang["install_database_passwordInput"] = "Mot de passe";
$lang["install_database_passwordPlaceholder"] = "le mot de passe de connexion";
$lang["install_database_databaseInput"] = "Base de données";
$lang["install_database_databasePlaceholder"] = "nom de la base de données";
$lang["install_database_operations"] = "Opérations";
$lang["install_database_saveButton"] = "Sauver la configuration";
$lang["install_database_pingButton"] = "Ping";
$lang["install_database_createButton"] = "Créer";
$lang["install_database_deployButton"] = "Déployer";
$lang["install_mail_form_legend"] = "Configuration des accès mail";
$lang["install_mail_hostInput"] = "Hôte";
$lang["install_mail_hostPlaceholder"] = "l'adresse du serveur de mail";
$lang["install_mail_portInput"] = "Port";
$lang["install_mail_portPlaceholder"] = "le port du serveur de mail";
$lang["install_mail_usernameInput"] = "Nom Utilisateur";
$lang["install_mail_usernamePlaceholder"] = "l'identifiant de connexion";
$lang["install_mail_passwordInput"] = "Mot de passe";
$lang["install_mail_passwordPlaceholder"] = "le mot de passe de connexion";
$lang["install_mail_fromMailInput"] = "Adresse émettrice";
$lang["install_mail_fromMailPlaceholder"] = "l'adresse d'émission";
$lang["install_mail_fromNameInput"] = "Nom émetteur";
$lang["install_mail_fromNamePlaceholder"] = "le nom de l'émetteur";
$lang["install_mail_testMailInput"] = "Adresse de test";
$lang["install_mail_testMailPlaceholder"] = "non sauvegardée";
$lang["install_mail_operation"] = "Opérations";
$lang["install_mail_saveButton"] = "Sauver la configuration";
$lang["install_mail_pingButton"] = "Ping";
$lang["install_application_form_legend"] = "Configuration de l'application";
$lang["install_application_baseUrlInput"] = "Url de base de l'application";
$lang["install_application_cronEnabledInput"] = "Autoriser l'envoi de tweet de manière différée";
$lang["install_application_cronEnabledHelp"] = "Veuillez rajouter dans votre table cron la commande <pre>* * * * * cd {path} && php do_cron.php</pre>";
$lang["install_application_saltInput"] = "Sel";
$lang["install_application_saltPlaceholder"] = "sel de l'application pour chiffrement et hachage";
$lang["install_application_defaultLanguageInput"] = "Langue par défaut";
$lang["install_application_operation"] = "Opérations";
$lang["install_application_saveButton"] = "Sauver la configuration";
$lang["install_autodestruct_guide"] = "Vous avez tout testé, tout configuré ? Alors un clic sur <em>autodestruction</em> pour supprimer cet installateur.";
$lang["install_autodestruct"] = "Autodestruction";

$lang["about_footer"] = "À Propos";
$lang["Parpaing_footer"] = "<a href=\"https://www.Parpaing.net/\" target=\"_blank\">Parpaing</a> est une application fournie par <a href=\"https://www.armagnet.fr\" target=\"_blank\">ArmagNet</a>";
?>