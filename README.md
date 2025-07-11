# PHP MVC Microframework

Ce projet est un cadre léger et flexible pour construire des applications web en PHP en utilisant le modèle d'architecture **Model-View-Controller (MVC)**. Il est conçu pour être simple à comprendre et à étendre, offrant une base solide pour vos projets.

---

## Fonctionnalités

Ce microframework inclut les fonctionnalités clés suivantes :

* **Routage** : Gère les requêtes HTTP et les associe aux contrôleurs et méthodes appropriés via `AltoRouter`.
* **Contrôleurs** :
    * **`MainController`**: Classe de base pour tous les contrôleurs, gérant les logiques communes. Support de l'authentification.
    * **Chargement automatique des modèles** : Les contrôleurs peuvent charger leurs modèles associés (`load_model`).
    * **Rendu des vues** : Affichage des vues, rendant les données disponibles et gestion des layouts.
* **Modèles** :
    * **`MainModel`**: Classe de base pour les modèles, offrant des méthodes courantes pour interagir avec la base de données (ex: `findAll`, `countAll`).
    * **Interaction PDO** : Utilise PDO pour des requêtes sécurisées et efficaces.
* **Vues** : Les vues sont séparées des contrôleurs et utilisent un système de template (`template.php`) pour inclure l'en-tête et le pied de page.
* **Gestion des erreurs** : Pages d'erreur 404 (non trouvée) et 500 (erreur serveur) personnalisées.
* **Gestion de session** : Support des sessions utilisateur (`session_start()`) pour la gestion de l'état.
* **Base de données** : Connexion à la base de données via PDO configurable via fichier séparé.

---

## Installation

Pour commencer avec ce microframework, suivez les étapes ci-dessous :

1.  **Cloner le dépôt** :
    ```bash
    git clone https://github.com/raphmn/microframework
    cd microframework
    ```

2.  **Installer les dépendances Composer** :
    Assurez-vous d'avoir [Composer](https://getcomposer.org/) installé.
    ```bash
    composer install
    ```
    Cela installera `AltoRouter` et toute autre dépendance nécessaire.

3.  **Configuration de la base de données** :
    Le framework se connecte à une base de données MySQL par défaut. Vous devez configurer vos informations d'identification dans `config.php`.
    ```php
    // config.php
    return [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'dbname'   => 'dev', // <-- Mettez votre nom de base de données
        'user'     => 'root', // <-- Votre utilisateur de base de données
        'password' => '',     // <-- Votre mot de passe de base de données
        'charset'  => 'utf8mb4',
    ];
    ```
    Assurez-vous que votre base de données `dev` (ou celle que vous avez nommée) existe et que les informations d'identification sont correctes.

4.  **Serveur web** :
    Configurez votre serveur web (Apache, Nginx, ou le serveur de développement de PHP) pour que le document root pointe vers le dossier `public/` (si vous en avez un, sinon le dossier racine du projet).
    Attention au fichier .htaccess si vous utilisez Apache

    Pour le **serveur de développement PHP** (pour des tests rapides) :
    ```bash
    php -S localhost:8000
    ```
    Ensuite, ouvrez votre navigateur et allez à `http://localhost:8000`.

---

## Utilisation

### Structure des dossiers

Le framework suit une structure de dossiers classique pour les applications MVC :

.\
├── public/              # Dossier public (point d'entrée du framework)\
│   └── index.php        # Fichier principal\
├── src/                 # Code source de l'application\
│   ├── Controller/      # Contrôleurs\
│   │   ├── MainController.php\
│   │   └── MyController.php\
│   ├── Model/           # Modèles\
│   │   ├── MainModel.php\
│   │   └── MyModel.php\
│   ├── View/            # Vues\
│   │   ├── Layouts/     # Layouts (template, header, footer, errors)\
│   │   └── MyController/  # Vues spécifiques aux contrôleurs\
│   ├── Database/        # Gestion de la base de données\
│   ├── Kernel.php       # Point d'entrée du framework\
│   ├── Routeur.php      # Gestion du routage\
│   ├── routes.php       # Définition des routes\
│   ├── config.php       # Configuration de la base de données\
│   └── includes.php     # Fichier d'inclusion (session_start, autoload, etc.)\
├── vendor/              # Dépendances Composer\
└── composer.json        # Fichier de configuration Composer\

### Définition des Routes

Les routes sont définies dans le fichier `routes.php`. Chaque route est un tableau avec la méthode HTTP, le chemin, la cible (Contrôleur#méthode) et un nom unique.

```php
// routes.php
return  [
    // ['METHOD', "route", "Controller#method", "UID"]  
    ['GET', '/', 'MyController#index', 'index'],
    ['GET', '/render', 'MyController#rendered_index', 'render'] 
];

```

- `'GET'` : Méthode HTTP (peut être POST, PUT, DELETE, etc.).
- `'/'` : Le chemin URL.
- `'MyController#index'` : Spécifie le contrôleur (MyController) et la méthode (index) à appeler.
- `'index'` : Un identifiant unique pour cette route, utile pour générer des URLs.

---

### Créer un Contrôleur

Tous les contrôleurs doivent étendre `App\Controller\MainController`.

```php
// src/Controller/MyController.php
<?php

namespace App\Controller;

class MyController extends MainController
{
    public function index()
    {
        echo "Hello world!";
    }

    public function rendered_index()
    {
        // Passer des données à la vue
        $this->set(
            [
                "title" => "Ma page rendue",
                "nofooter" => 0 // Ne pas afficher le pied de page. Le fait de déclarer la variable desactive le pied de page. fonctionne aussi pour header.
            ]);

        // Rendre la vue correspondante (MyController/rendered_index.php)
        $this->render();
    }
}
```

---

### Créer une Vue

Les vues sont des fichiers PHP situés dans `src/View/<NomDuContrôleur>/<NomDeLaMéthode>.php`.  
Pour la méthode `rendered_index` de `MyController`, la vue serait `src/View/MyController/rendered_index.php`.

```html
<h1>Bienvenue sur <?= $title ?? 'ma page' ?></h1>
<p>Ceci est une page rendue par le contrôleur.</p>
```

Les variables passées via `$this->set()` dans le contrôleur sont directement accessibles dans la vue.

---

### Créer un Modèle

Les modèles doivent étendre `App\Model\MainModel`.  
Le nom du modèle doit être `[NomDuContrôleur]Model` (ex: `MyModel` pour `MyController`).

```php
// src/Model/MyModel.php
<?php

namespace App\Model;

use PDO;

class MyModel extends MainModel
{
    protected string $table = "articles"; // Exemple: changer la table par défaut

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    public function getLatestArticles(int $limit = 5): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        return $this->query($sql, ['limit' => $limit]);
    }
}
```

Le modèle `MyModel` sera automatiquement chargé par `MyController` via la méthode `load_model()`.  
Vous pouvez ensuite y accéder via `$this->model`.

---


### Licence

Ce projet est sous licence Apache version 2.  
Voir le fichier `LICENSE` pour plus de détails.
