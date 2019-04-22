<?php

namespace App\Controller;

use Beaver\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return '<img src="https://banner2.kisspng.com/20180124/thw/kisspng-beaver-clip-art-hello-beaver-5a686ba614b066.0283876115167927420848.jpg">';
    }
}
