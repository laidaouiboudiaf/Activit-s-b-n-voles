<?php

class Benevoles extends Controller {
  public function index() {
//affichage de la page d'accueil, appel presentation juste en dessous
    $this->presentation();
  }
  
  public function presentation() {
//liste des evenements en bd et affichage complet
    $this -> c_eventsShow();
  }

  public function c_eventsShow() {
//recuperation des caracteristiques de tous les evenements
    $events = $this->benevoles->m_showEvents();
    //var_dump($events);
    $this->loader->load('events', ['title'=>'Présentation des actions', 'events'=>$events]);
  }

  private function redirect_unlogged_user() {
//si utilisateur pas loggé
    if (!$this->sessions->user_is_logged()) {
      header('Location: /index.php/sessions/sessions_new');
      return true;
    }
    return false;
  }

  public function c_candidate_create($logged_user){
//selection evenements des ouverts à candidature 
    $futursEvents = $this->benevoles->m_listFutursEvents();
    $this->loader->load ('eventChoise', ['title'=>'Choisir un événement',
                                          'futursEvents'=>$futursEvents]);
  }

  public function c_skill_select(){
//récupération du nom de l'evenement en cours
    $nameEvent = $_POST['nameEvent'];
//récup liste des taches de l evenement
    $skills = $this->benevoles->m_show_skill($nameEvent);
    $this->loader->load ('skillChoise', ['title'=>'Qualifications',
                                          'nameEvent'=>$nameEvent,
                                          'skills'=>$skills]);
  }

  public function c_schedule_select($nameEvent){
    error_reporting(E_ALL);
    $loginUser = $_SESSION['logged_user']->loginUser;
//mettre les skills dans table qualification avec m_add_skill
    if(!empty($_POST))
      $skillChoise = $_POST['skill'];
    foreach ($skillChoise AS $skill) 
      $this->benevoles->m_add_skill($skill, $loginUser); 
//recup liste des créneaux
    $schedules = $this->benevoles->m_show_schedule();
//afficher page scheduleChoise
    $this->loader->load ('scheduleChoise', ['title'=>'Disponibilités',
                                            'nameEvent'=>$nameEvent,
                                            'schedules'=>$schedules]);
  
 }

  public function c_dispo_add(){
//recuperation des caracteristiques de l utilisateur connecté
    $loginUser = $_SESSION['logged_user']->loginUser;
//pour les dispo choisies appeler m_add_dispo qui ajoute les dispos de logged user à disponibilite
    if(!empty($_POST))
      $disposChoise = $_POST['schedule'];
    foreach ($disposChoise AS $dispo) 
      $this->benevoles->m_add_schedule($dispo, $loginUser);
    $this->loader->load ('pageFinaleBene', ['title'=>'Un grand Merci']);
  }

}
