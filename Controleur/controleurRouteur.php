<?php

class controleurRouteur extends controleur
{

  protected $controller;

  public function process($params)
  {
    $parsedUrl = $this->parseUrl($params[0]);

    if (empty($parsedUrl[0]))
    {
      $this->redirect('article/home');
    }

    // The controller is the 1st URL parameter
    $controllerClass = 'controleur'.array_shift($parsedUrl);

    if (file_exists('Controleur/' . $controllerClass . '.php'))
    $this->controleur = new $controllerClass;
    else
    $this->redirect('error');

    $this->controleur->process($parsedUrl);
    $this->data['title'] = $this->controleur->head['title'];
    $this->data['description'] = $this->controleur->head['description'];
  }


  function parseUrl($url)
  {
    $parsedUrl = parse_url($url);
    $parsedUrl["path"] = ltrim($parsedUrl["path"], "/");
    $parsedUrl["path"] = trim($parsedUrl["path"]);

    $explodedUrl = explode("/", $parsedUrl["path"]);

    return $explodedUrl;
  }
}
