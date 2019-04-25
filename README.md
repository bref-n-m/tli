# Projet TLI

## Injection de dépendance
```yaml
# /data/www/app/config/DependencyInjection/di.yaml

services:
  service_salut: # Nom du service
    class: 'App\Salut'
    parameters: # Correspond aux paramètres du constructeur de la classe App\Salut
      $serviceBonjour: '@nom_service_bonjour'
      $aParam: 'b'
      $serviceTruc: '@truc'
      $anOtherParam: 'c'

  truc:
    class: 'App\Truc'
    parameters:
      $a: 'a'
      $b: '@nom_service_bonjour'

  nom_service_bonjour:
    class: 'App\Bonjour'
```

### Classe correspondant au service `sercice_salut`
```php

# /data/www/app/src/App/Salut.php

<?php

namespace App;

use App\Bonjour;
use App\Truc;

class Salut
{
    /** @var Truc */
    private $serviceTruc;
    
    /** @var string */
    private $aParam;

    /** @var string */
    private $anOtherParam;

    /** @var Bonjour */
    private $serviceBonjour;
    
    public function __construct(Truc $serviceTruc, string $aParam, string $anOtherParam, Bonjour $serviceBonjour)
    {
        $this->serviceTruc = $serviceTruc;
        $this->aParam = $aParam;
        $this->anOtherParam = $anOtherParam;
        $this->serviceBonjour = $serviceBonjour;

    }
}
```

## Routing
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
      slug: '[a-zA-Z\-]+'
      id: '\d+'
```

## Controllers
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
        /** @var Salut */
        $serviceSalut = $this->get('sercice_salut'); // Récupération du service 'service_salut'
        
        /** @var Router $router */
        $router = $this->get('router');

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

## Twig
Tous les fichiers twig se trouvent dans le répertoire `/data/www/app/src/App/templates/`

Les fonctions suivantes, ont étés recrées pour notre architecture, et doivent être utilisées:
* `asset()` :arrow_right: pour charger les fichiers statiques présent dans `/data/www/app/public/`
* `path()` :arrow_right: pour créer un lien vers une action

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
