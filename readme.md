READ ME TEMPORAIRE JUSTE POUR CES JOURS

COMMENT INSTALLER LE PROJET

Les étapes à suivre sont les suivantes:

installer le gestionnaire de dépendances "composer"
installer un outil de développement PHP comme xampp ou wampServer

Utiliser composer pour installer/mettre à jour les dépendances

Cloner ce dépôt dans le dossier htdocs (pour xampp) ou www (pour WampServer)
Créer la base de données sous mysql par exemple (donner le nom que vous voulez)
Configurer les paramètres de connexion à la base de données dans le fichier .env

#exemple
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your database name
DB_USERNAME=your username
DB_PASSWORD=your password


Créer les tables dans la base de données en tapant à l'invite de commande etant positionné sur le répertoire de votre site: 
$ php artisan migrate 

Créer manuellement un utilisateur (Application) dans la base de données (table apps) ayant les paramètres suivants:
key=TestKey
name=noApp
login=noApp
password=noApp
state=0

Ajouter un template de rapport (table report_template) suivant:
report_template_name ---> default
report_template_content --->
\<div class="container" style="margin: auto; width: 100%; vertical-align: middle;background: rgba(200, 250, 200, 0.8); border-radius: 5px; padding: 15px; border: 2px solid green;">
	\<div class="row" style="margin-bottom: 10px; margin-top: 10px; padding: 0px 15px 0px 15px;">
		\<div class="col-md-12 col-sm-12 col-xs-12">
			\<div class="form-group alert alert-danger alert-dismissable">
				\<div class="title" class="margin-bottom: -15px; padding: 0px 15px 0px 15px;">
					\<h2>#title_1#\</h2>
				\</div>
				\<hr />
				\<div class="subtitle" class="margin-top: -15px; font-style: italic; padding: 0px 15px 0px 15px;">
					\<h4>#subtitle_2#\</h4>
				\</div>
			\</div>
		\</div>
	\</div>
	\<div class="row" style="margin-bottom: 10px; margin-top: 10px; padding: 0px 15px 0px 15px;">
		\<div class="body col-md-12 col-sm-12 col-xs-12" style="background-color: white; padding: 15px; border-radius: 2px; border: 1px solid gray;">
			#content_3#
		\</div>
	\</div>
	\<div class="row" style="margin-bottom: 10px; margin-top: 10px; padding: 0px 15px 0px 15px;">
		\<div class="col-md-12 col-sm-12 col-xs-12">
			\<div class="footer form-group alert alert-danger alert-dismissable" style="font-style: italic; text-align: center; font-weight: bold;">
				\<hr />
				#footer_4#
			\</div>
		\</div>
	\</div>
\</div>

		
Ajouter le template d'email (table email_temlate) suivant:
apps_id  ---> ID d'une application
email_template_name ---> default
email_template_content --->
\<!doctype html>
\<html>
	\<head>
		\<title>A sample of document template\</title>
		\<style>
			html{display:table;height:100%; width:100%;}
			body{height:100%; display:table; background-color: white; display: flex;}
			.container{display:table-cell;margin: auto; width: 100%; vertical-align: middle;background: rgba(200, 250, 200, 0.8); border-radius: 5px; padding: 15px; border: 2px solid green;}
			.row{margin-bottom: 10px; margin-top: 10px; padding: 0px 15px 0px 15px;}
			.title{margin-bottom: -15px; padding: 0px 15px 0px 15px;}
			.subtitle{margin-top: -15px; font-style: italic; padding: 0px 15px 0px 15px;}
			hr{}
			.body{background-color: white; padding: 15px; border-radius: 2px; border: 1px solid gray;}
			.footer{font-style: italic; text-align: center; font-weight: bold;}
		\</style>
	\</head>
	\<body>
		\<div class="container">
			\<div class="row">
				\<div class="col-md-12 col-sm-12 col-xs-12">
					\<div class="form-group alert alert-danger alert-dismissable">
						\<div class="title">
							\<h2>#title_1#\</h2>
						\</div>
						\<hr />
						\<div class="subtitle">
							\<h4>#subtitle_2#\</h4>
						\</div>
					\</div>
				\</div>
		    \</div>
		    \<div class="row">
				\<div class="body col-md-12 col-sm-12 col-xs-12">
		    		#content_3#
		    	\</div>
		    \</div>
		    \<div class="row">
				\<div class="col-md-12 col-sm-12 col-xs-12">
					\<div class="footer form-group alert alert-danger alert-dismissable">
						\<hr />
						#footer_4#
					\</div>
				\</div>
		    \</div>
		\</div>
	\</body>
\</html>



Démarrer l'application en tapant à l'invite de commande : 
$php artisan serve

Consulter le rendu graphique dans le navigateur via l'URL: localhost:8000/home
pour continuer graphiquement, connectez vous avec les paramètres (login=noApp, password=noApp)


Télécharger et installer le logiciel "postman" pour tester les services REST de notre application
taper les URLs suivantes dans la console de postman:


Pour tester la génération d'un rapport
url = localhost:8000/report/generate
method = POST
body a les paramètres 
     key = TestKey
	 data = {
				"template":"default",
				"data":{
					"title":"Mon jolie rapport!",
					"subtitle":"Un sous-titre pour mon rapport",
					"content":"\<h4>Du contenu HTML personnalisé (un tableau, une image...)\</h4>\<table width=\"100%\" class=\"test\" style=\"border-collapse:collapse; border:0px; margin-top:50px;\">\n\t\t\t    \<thead>\n\t\t\t    \t\<tr>\n\t\t\t    \t\t\<th style=\"border:1px solid; padding:15px; text-align:center;\" colspan=\"5\">ici le titre\<\/th>\n\t\t\t    \t\<\/tr>\n\t\t\t        \<tr>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" width=\"18%\" text-align=\"center\">First Name \<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\"> Last Name \<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\">Sexe\<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\">Phone number\<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\">Email address\<\/th>\n\t\t\t        \<\/tr>\n\t\t\t    \<\/thead>\n\t\t\t    \<tbody>\n\t\t\t        \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t        \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t        \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t    \<\/tbody>\n\t\t\t    \<tfoot>\n\t\t\t       \<tr>\n\t\t\t       \t\t\<td colspan=\"5\" style=\"border:1px solid; padding:15px; text-align:center;\">\u00e9tat des r\u00e9alisations sur le projet \n\t\t\t       \t    \<\/td>\n\t\t\t       \<\/tr>\n\t\t\t    \<\/tfoot>\n\t\t\t\<\/table> \n\t    ",
					"footer":"copyright %26copy; webcolf 2018."
				}
			}
	 type = pdf
	 
Le rapport généré est consultable via l'URL renvoyée ou sous le dossier : storage

Pour tester l'envoie d'email
url = localhost:8000/mail/send
method = POST
body a les paramètres 
     key = TestKey
	 data = {
				"to":"ndadjimaxime@yahoo.fr",
				"subject":"Testing Email sending",
				"template":"default",
				"data":{
					"title":"Mon jolie rapport!",
					"subtitle":"Un sous-titre pour mon rapport",
					"content":"\<h4>Du contenu HTML personnalisé (un tableau, une image...)\</h4>\<table width=\"100%\" class=\"test\" style=\"border-collapse:collapse; border:0px; margin-top:50px;\">\n\t\t\t    \<thead>\n\t\t\t    \t\<tr>\n\t\t\t    \t\t\<th style=\"border:1px solid; padding:15px; text-align:center;\" colspan=\"5\">ici le titre\<\/th>\n\t\t\t    \t\<\/tr>\n\t\t\t        \<tr>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" width=\"18%\" text-align=\"center\">First Name \<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\"> Last Name \<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\">Sexe\<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\">Phone number\<\/th>\n\t\t\t            \<th style=\"border:1px solid; padding:15px;\" scope=\"col\">Email address\<\/th>\n\t\t\t        \<\/tr>\n\t\t\t    \<\/thead>\n\t\t\t    \<tbody>\n\t\t\t        \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t        \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t         \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t        \<tr>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\"> Colfina\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Webcolf\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">Masculin\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">681542336\<\/td>\n\t\t\t            \<td style=\"border:1px solid; padding:15px;background-color: #e4f0f5;\">smartcoders.webcolf@gmail.com\<\/td>\n\t\t\t        \<\/tr>\n\t\t\t    \<\/tbody>\n\t\t\t    \<tfoot>\n\t\t\t       \<tr>\n\t\t\t       \t\t\<td colspan=\"5\" style=\"border:1px solid; padding:15px; text-align:center;\">\u00e9tat des r\u00e9alisations sur le projet \n\t\t\t       \t    \<\/td>\n\t\t\t       \<\/tr>\n\t\t\t    \<\/tfoot>\n\t\t\t\<\/table> \n\t    ",
					"footer":"copyright %26copy; webcolf 2018."
				}
			}

Ils existent d'autres services, mais nous avons présenté uniquement les plus importants