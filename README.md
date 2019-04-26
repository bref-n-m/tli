# Projet TLI

* [Injection de dépendance](#injection-de-dépendance)
  * [Classe correspondant au service `rs6`](#classe-correspondant-au-service-rs6)
* [Routing](#routing)
  * [Service de routing](#service-de-routing)
* [Controllers](#controllers)
  * [Récupération des paramètres](#récupération-des-paramètres)
* [Twig](#twig)

## Injection de dépendance
L'injection de dépendance c'est quoi ?

> Il consiste à créer dynamiquement (injecter) les dépendances entre les différents objets en s'appuyant sur une description (fichier de configuration ou métadonnées) ou de manière programmatique.
[Wikipédia](https://fr.wikipedia.org/wiki/Injection_de_d%C3%A9pendances)

Avec un exemple:

On à une classe `Voiture`, cette classe prend en paramètres de son constructeur:
* $moteur de type `App\Moteur`
* $roues de type `App\Roues`
* $marque de type `string`
* $modele de type `string`

A partir de cette classe on veut définir un service `RS6`, pour ce faire on utilise le fichier de configuration suivant:

```yaml
# /data/www/app/config/DependencyInjection/di.yaml

services:
  rs6:
    class: 'App\Voiture'
    parameters:
      $moteur: '@v8' # on veut injecter le service v8
      $roues: '@18pouces' # on veut injecter le service 18pouces
      $marque: 'Audi'
      $modele: 'RS6'

  v8:
    class: 'App\V8' # App\V8 étend de App\Moteur
    parameters:
      $vis: '@ecrou'

  18pouces:
    class: 'App\18Pouces' # App\V8 étend de App\Roues
    parameters:
      $vis: '@ecrou'
      
  ecrou:
    class: 'App\ecrou'
    parameters:
      $longueur: '20'
```

Ainsi, quand on voudra récuperer le service `rs6`, le système d'injection de dépendance utilisera la configuration définie pour passer au constructeur les bons paramètres.

Les paramètres sont définis sous la clé `parameters`.

Pour injecter un autre service, on le préfixera d'un `@`.

### Classe correspondant au service `rs6`
```php
# /data/www/app/src/App/Voiture.php

<?php

namespace App;

use App\Moteur;
use App\Roues;

class Voiture
{
    /** @var Moteur */
    private $serviceMoteur;
    
    /** @var Roues */
    private $serviceRoues;

    /** @var string */
    private $marque;

    /** @var string */
    private $modele;
    
    public function __construct(Moteur $serviceMoteur, Roues $serviceRoues, string $marque, string $modele)
    {
        $this->serviceMoteur = $serviceMoteur;
        // ...
    }
}
```

On voit donc que si on souhaite créer une `Voiture` avec d'autres paramètres, il suffit de créer un nouveau service.

## Routing
Pour gérer le routing de l'application, un fichier de configuration est nécessaire. Il suit la forme suivante:

```yaml
# /data/www/app/config/Routing/routes.yaml

routes:
  index:
    path: '/'
    controller: 'App\Controller\DefaultController::index'

  complex:
    path: '/some/complex/route/:id-:slug'
    controller: 'App\Controller\DefaultController::complex'
    parameters:
      slug: '[a-zA-Z\-]+' # au moins une lettre minuscule ou majuscule ou caractère -
      id: '\d+' # au moins un chiffre
```

Chaque route est identifiée par son nom `index` et `complex` dans l'exemple.

| Clé | Description |
|:---:| ----------- |
| `path` | Donne l'`uri` de la route |
| `controller` | Une route correspond à une méthode d'un controller. Ainsi, la valeur `App\Controller\DefaultController::index`, précise que la méthode `index` de la classe `App\Controller\DefaultController`, sera utilisé pour la route en question. |
| `parameters` | Une route peut prendre des paramètres, chaque paramètre est une expression régulière. Le site [regex 101](https://regex101.com/) permet de tester facilement vos expressions régulires.<br>Dans la clé `path`, les paramètres seront préfixés par `:`, exemple pour les paramètres `id` et `slug`: `path: '/some/complex/route/:id-:slug'`<br>Ces paramètres seront ensuite envoyés en paramètres à la méthode du controller, voir [récupération des paramètres](#récupération-des-paramètres) |

### Service de routing

Un service de routing appelé `router` permet de générer des `uri` vers les actions de l'application, par le biais de la méthode `generatePath(...)`. Cette méthode prend en premier paramètre, le nom de la route, et en second, un tableau correspondant aux paramètres de celle-ci. Voir l'exemple ci-dessous.

```php
/** @var Router $router */
$router = $this->get('router'); // recupération du service router.

$uri = $router->generatePath('complex', [
  'id'   => 123,
  'slug' => 'salut-ca-va',
]);
```

## Controllers
Les pages de notre application correspondent chacunes à une action d'un controller.

Chaques action doit retourner un objet de type `Beaver\Response\Response`.

La méthode `$this->render('template.html.twig', ['parameter' => 'lorem'] /* tableau à passer à twig */);` renvoit un objet `Beaver\Response\Response`. Elle peut donc être utilisée par une action pour rendre une template twig (voir l'exemple ci-après).

Dans le cas de web service, vous pouvez renvoyer une `Beaver\Response\JsonResponse` (qui étend de `Beaver\Response\Response`) (voir l'exemple ci-après).

```php
# /data/www/app/src/App/Controller/DefaultController.php

<?php

namespace App\Controller;

use App\Salut;
use Beaver\Controller\AbstractController;
use Beaver\Response\JsonResponse;
use Beaver\Response\Response;
use Beaver\Router;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', [
            'name' => 'Beaver',
        ]);
    }

    public function complex(string $slug, int $id)
    {
        /** @var Router $router */
        $router = $this->get('router'); // Récupération du service 'router'

        return new JsonResponse([
            'id'    => $id,
            'slug'  => $slug,
            'route' => $router->generatePath('complex', [
                'id'   => 123,
                'slug' => 'salut-ca-va',
            ])
        ]);
    }
}
```

### Récupération des paramètres

## Twig
Tous les fichiers twig se trouvent dans le répertoire `/data/www/app/src/App/templates/`

Les fonctions suivantes, ont étés recrées pour notre architecture, et doivent être utilisées:
* `asset()` :arrow_right: pour charger les fichiers statiques présent dans `/data/www/app/public/`
* `path()` :arrow_right: pour créer un lien vers une action (utilise le service `router`, voir [service de routing](#service-de-routing))

Exemple d'utilisation de ces méthodes:
``` twig
<html>
<head>
    <link rel="stylesheet" href="{{ asset('style.css') }}"> {# vient chercher le fichier style.css dans le dossier /data/www/app/public/ #}
</head>
<body>
{% include 'test.html.twig' %}
<p>Hi {{ name }}</p>
{# créé un lien vers l'action complex et avec les paramètres id à 12 et slug à coucou #}
<p><a href="{{ path('complex', {'id': '12', 'slug': 'coucou'}) }}">Complex route</a></p> 
<br>
<img src="https://banner2.kisspng.com/20180124/thw/kisspng-beaver-clip-art-hello-beaver-5a686ba614b066.0283876115167927420848.jpg">
</body>
</html>
```

[Doc de twig](https://twig.symfony.com/doc/2.x/)
