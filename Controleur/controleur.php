<?php
abstract class controleur
{

  protected $data = array();
  protected $view = "";
  protected $head = array('title' => '', 'description' => '');

  abstract function process($params);

  public function renderView()
  {
    if ($this->view)
    {
      extract($this->data);
      require("views/" . $this->view . ".phtml");
    }
  }

  public function redirect($url)
  {
    header("Location: /$url");
    header("Connection: close");
    exit;
  }
}
