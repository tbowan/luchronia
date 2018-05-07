; <?php
; /*

[Constant]
DT = 3786822000
BASE_DATA = /var/Luchronia/data/Files
IMUNE_DELAY = 1

; Configuration du server
[Server]
class = "\Quantyl\Server\CmdLineServer"

; Configuration de la base de données
[dao]
driver    = mysql
hostname  = localhost
database  = luchronia
username  = root
password  = ""
nsBase    = "Model"
nsReplace = "_"

; Configuration des services
; - base : permet de configurer le namespace de base où sont les services
[service]
base = "Scripts"

; Configuration des sessions
; - class : classe php implémentant le mécanisme
;           "" ou "native" n'utilise aucune classe mais laisse les mécanismes natifs
;           la classe doit étendre la classe \Quantyl\Session\Session
; - tablename : le nom de la table en base de donnée qui stocke les sessions
;           facultatif, ne doit être spécifié que si on utilise la classe
;           "\Quantyl\Session\DataBase"
[session]
class     = "\Quantyl\Session\Dummy"

; Configuration du backoffice
; - mode : spécifie le mécanisme de gestion des droits d'accès en mode admin
;          si rien n'est renseigné, l'accès est libre.
;          si "database", alors la base de donnée est lue pour le système de droits
; - group_table : table stockant les groupes d'utilisateurs
; - role_table  : table stockant quel utilisateur est dans quel groupe
; - group_name  : nom du group des administrateurs
[admin]
mode = "database"
group_table = "identity_group"
role_table  = "identity_role"
group_name  = "administrateur"

; Configuration des vues
; - errorDecorator : classe decorator à utiliser en cas d'erreur
; - errorWidget    : classe pour l'affichage de l'erreur
[view]
decoraror = "\Decorator"
error     = "\Widget\Exception"

; Configuration de l'internationnalisation
; - lang           : langue par défaut
; - class          : nom de la classe implémentant la traduction
; - directory      : répertoire contenant les fichiers de traduction
;                    si on utilise "\Quantyl\I18n\IniTranslator"
; - langtable      : table contenant la liste des langues
;                    si on utilise "\Quantyl\I18n\Bddranslator"
; - translatetable : table contenant la liste des traductions
;                    si on utilise "\Quantyl\I18n\Bddranslator"
[I18n]
lang           = "fr"
class          = "\Quantyl\I18n\BddTranslator"
directory      = "I18n"
langtable      = "i18n_lang"
translatetable = "i18n_translation"

; Configuration du blog
; - postbypage : nombre de billets à afficher sur une page
[blog]
postbypage     = 5

; */