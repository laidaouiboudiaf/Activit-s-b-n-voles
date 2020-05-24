<?php

$config = [
  'default_controller' => 'Benevoles',
  'default_method' => 'index',
  'core_classes'=> ['Controller','Loader','Model'],
  'models'=>['Benevoles','Organisateurs','Users','Sessions']
];

// $config = [
//   'default_controller' => 'Gallery',
//   'default_method' => 'index',
//   'core_classes'=> ['Controller','Loader','Model'],
//   'models'=>['Gallery','Users','Sessions']
// ];

// ++++++++++++++++++++++++++++++++++++++++++++++++
// On charge les class : Controller Loader et Model
// ++++++++++++++++++++++++++++++++++++++++++++++++

foreach ($config['core_classes'] as $classname) require "core/$classname.php";

function generate_error_404($exception) {
  header("HTTP/1.0 404 Not Found");
  include 'views/header.php';
  echo $exception->getMessage();
  include 'views/footer.php';
}


function get_path_elements() {
  //var_dump($_SERVER);
  if (!isset($_SERVER['PATH_INFO'])) { return []; }
  $path_info = $_SERVER['PATH_INFO'];
  $path_elements = explode('/', $path_info);
  array_shift($path_elements);
  return $path_elements; 
}

function path_contains_controller_name($path_elements) {
  return $path_elements!==null && count($path_elements)>=1; 
}

function path_contains_method_name($path_elements) {
  return $path_elements!==null && count($path_elements)>=2;
}

function get_controller_name($path_elements) {
  global $config;
  
  // operateur ternaire 
  // L'expression (expr1) ? (expr2) : (expr3) renvoie valeur de expr2 si expr1 vrai, renvoie expr3 si expr1 faux
  
  return (path_contains_controller_name($path_elements))
      ?  $path_elements[0] 
      : $config['default_controller'];
}

function get_parameters($path_elements) {
  if (count($path_elements)<=2) { return []; }
  return array_slice($path_elements, 2);
}

function get_method_name($path_elements) {
  global $config;
  return (path_contains_method_name($path_elements))
      ?  $path_elements[1] 
      : $config['default_method'];
}

function create_controller($controller_name) {
  $controller_classname = ucfirst(strtolower($controller_name));
  $controller_filename = 'controllers/'.$controller_classname.'.php';
  if (!file_exists($controller_filename)) { throw new Exception("Le fichier $controller_classname.'.php' n'existe pas"); }
  include $controller_filename;
  if (!class_exists($controller_classname)) { throw new Exception("La classe n'existe pas"); }
  return new $controller_classname();
}

function call_controller_method($controller, $method_name, $parameters) {
 $reflectionObject = new ReflectionObject($controller);
  if (! $reflectionObject->hasMethod($method_name)) { throw new Exception("Methode inconnue"); }
  $reflectionMethod = $reflectionObject->getMethod($method_name);
  if ($reflectionMethod->getNumberOfParameters()!=count($parameters)){ throw new Exception("Nombre de parametre incorrect"); }
  $reflectionMethod->invokeArgs($controller, $parameters);
}

// ++++++++++++++++++++++++++++++++++++++
// Programme principal
// ++++++++++++++++++++++++++++++++++++++
try {

  // on affecte à path_elements un tableau de string correspondant aux parties de l'uri 
  // exemple : index.php/gallery/albums_show/themes -> path_elements {"gallery","albums_show","themes"}
  $path_elements = get_path_elements();
  
  // On retourne la 1er valeur de path_elements ou  default_controller de config (Gallery)
  // dans l'exemple : gallery
  $controller_name = get_controller_name($path_elements);
  
  // On retourne la 2eme valeur de path_elements ou default_method de config (index)
  // dans l'exemple albums_show
  $method_name = get_method_name($path_elements);
  
  // On cree un controller 
  // -> Creation d'un loader 
  // -> Chargement des models de $config['models'] en ajoutant _model... donc chargement de Gallery_model
  $controller = create_controller($controller_name);
  
  // on retourne les dernieres valeurs de path_elements 
  $parameters = get_parameters($path_elements);
  
  // On appel la class controlle avec le nom de la method et les arguments en utilisant un ReflectionObject
  call_controller_method($controller, $method_name, $parameters);

} catch (Exception $exception) {
  generate_error_404($exception);
}


//echo 'path_elements : '; var_dump($path_elements); echo '<br>';
//echo 'controller_name : '; var_dump($controller_name);echo '<br>';
//echo 'method_name : '; var_dump($method_name);echo '<br>';
//echo 'controller : '; var_dump($controller);echo '<br>';
//echo 'parameters : '; var_dump($parameters);echo '<br>';


?>