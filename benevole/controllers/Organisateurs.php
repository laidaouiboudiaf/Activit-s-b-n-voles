<?php

class Organisateurs extends Controller {

  public function c_event_list($logged_user) {
    //recup liste evenements passes
    $pastsevents = $this->organisateurs->m_listPastsEvents($logged_user);
//recup liste evenements futurs
    $futursevents = $this->organisateurs->m_listFutursEvents($logged_user);
    $this->loader->load('createOrChgeEvent', ['title'=>"Création ou modification d'un événement",
                                              'pastsevents'=>$pastsevents,
                                              'futursevents'=>$futursevents]);
  }

  public function c_event_createNewName($logged_user){ 
//donner un nom au nouvel evenement pour acceder au formulaire de creation 
    try{
      $nameEvent = filter_input(INPUT_POST, 'nameEvent');
      $loginUser=$logged_user;
      if ($nameEvent=="")
        echo "vous n'avez pas entrer de nom pour votre nouvel evenement.";
      else{
        $this->loader->load ('event_new', ['title'=>'Renseignement d\'un nouvel evenement',
                                          'nameEvent'=>$nameEvent,
                                          'loginUser'=>$loginUser]);
      }
    }catch (Exception $e) {
      $data = ['error' => $e->getMessage(), 'title'=>'S\'inscrire'];
      $this->loader->load('users_new', $data );
    }
  }
   
  public function c_event_create(){
//recuperer toutes les infos du formulaire
    try{
      $nameEvent = filter_input(INPUT_POST, 'nameEvent');
      $dateEvent = filter_input(INPUT_POST, 'dateEvent');
      $deadline = filter_input(INPUT_POST, 'deadline');
      $typeEvent = filter_input(INPUT_POST, 'typeEvent');
      $descriptionEvent = filter_input(INPUT_POST, 'descriptionEvent');
      $road = filter_input(INPUT_POST, 'road');
      $mailEvent = filter_input(INPUT_POST, 'mailEvent');
      $website = filter_input(INPUT_POST, 'website');
      $picture = filter_input(INPUT_POST, 'picture');
      $CP = filter_input(INPUT_POST, 'CP');
      $loginUser = filter_input(INPUT_POST, 'loginUser');
//créer le nouvel evenement  
      $this->organisateurs->m_create_event($nameEvent, $dateEvent, $deadline, $typeEvent, $descriptionEvent, $road, $mailEvent, $website, $picture, $CP, $loginUser);
//recuperer les caractéristiques de l evenement en cours
      $event = $this->organisateurs->m_show_event($nameEvent);
//recuperer la listes des taches existantes
      $tasks = $this->organisateurs->m_show_tasks();
      $this->loader->load('pageTache', ['title'=>'Création d\'un événement',
                                        'event'=>$event,
                                        'tasks'=>$tasks]);
    }catch (Exception $e) {
      $data = ['error' => $e->getMessage(), 'title'=>'S\'inscrire'];
      $this->loader->load('users_new', $data );
    }
   }

  public function c_event_change($idEvent){
    $this->organisateurs->m_change_event($idEvent);
    $this->loader->load ('pageFin', ['title'=>'Page du bout du travail']);
  }

  public function c_event_duplicate($idEvent){
    echo "programmer la duplication d un evenement";
    $this->loader->load ('pageFin', ['title'=>'Page du bout du travail']);
  }

  public function c_task_add($nameEvent){
//ajouter une nouvelle tache en bd
    $nameTask = filter_input(INPUT_POST, 'nameTask');
    $this->organisateurs->m_add_task($nameTask);
//recuperer la listes des taches existantes
    $tasks = $this->organisateurs->m_show_tasks();
//recuperer les caractéristiques de l evenement en cours
    $event = $this->organisateurs->m_show_event($nameEvent);
    $this->loader->load('pageTache', ['title'=>'Création d\'un événement',
                                      'event'=>$event,
                                      'tasks'=>$tasks]);
  }

  public function c_composition_add(){
//recuperer les caractéristiques de l evenement en cours
    $nameEvent = $_POST['nameEvent'][0];
    $event = $this->organisateurs->m_show_event($nameEvent);
//recuperation des données du formulaire
    foreach ($_POST['nameResp'] as $key => $value) {
      if($value!=""){
        $idEvent = $_POST['idEvent'][$key];
        $nameEvent = $_POST['nameEvent'][$key];
        $idTask = $_POST['idTask'][$key];
        $nameTask = $_POST['nameTask'][$key];
        $nameResp = $_POST['nameResp'][$key];
        $phoneResp = $_POST['phoneResp'][$key];
//ajouter compositions en bd et recuperation caractéristiques  
        $composition = $this->organisateurs->m_add_composition($idEvent, $nameEvent, $idTask, $nameTask, $nameResp, $phoneResp);
      }
    }
//recuperation des taches deja affectées à l'evenement
    $listTasks = $this->organisateurs->m_show_tasksEvent($nameEvent);
//recuperation de tous les créneaux de la base pour les proposer et eviter les redondances
    $schedules = $this->organisateurs->m_show_schedules();
//recupearation de toutes les competences de la base pour les proposer et eviter les redondances
    $skills = $this ->organisateurs->m_show_skills();
    //var_dump($skills);
    $this->loader->load ('schedule_add', ['title'=>'Choix des créneaux',
                                          'event'=>$event,
                                          'listTasks'=>$listTasks,
                                          'skills'=>$skills,
                                          'schedules'=>$schedules]);
  }

  public function c_composition_delete($nameTask){
    echo "methode task delete doit enlever le nom de la tache ou la case est cochée sur la page tache";
    $this->loader->load ('pageFin', ['title'=>'Page du bout du travail']);
  }

  public function c_schedule_add($nameEvent){
//recuperer les caractéristiques de l evenement en cours
    $event = $this->organisateurs->m_show_event($nameEvent);
//recuperer la liste des taches affectes a l evenement en cours
    $listTasks = $this->organisateurs->m_show_tasksEvent($nameEvent);
//recuperation des données du formulaire ajout de creneaux et mise en bd
    $hDeb = $_POST['hDeb'];
    $hFin = $_POST['hFin'];
    $this->organisateurs->m_add_schedule($hDeb, $hFin);
//recuperation liste des créneaux en bd pour les proposer et eviter les redondances
    $schedules = $this->organisateurs->m_show_schedules();
    $this->loader->load ('schedule_add', ['title'=>'Choix des créneaux',
                                          'event'=>$event,
                                          'listTasks'=>$listTasks,
                                          'schedules'=>$schedules]);
  }

  public function c_skill_add($nameEvent){
//recuperer les caractéristiques de l evenement en cours
    $event = $this->organisateurs->m_show_event($nameEvent);
//recuperer la liste des taches affectes a l evenement en cours
    $listTasks = $this->organisateurs->m_show_tasksEvent($nameEvent);
//recuperation liste des créneaux en bd pour les proposer et eviter les redondances
    $schedules = $this->organisateurs->m_show_schedules();
//recuperation des données du formulaire ajout des compétences en bd
    $nameSkill = $_POST['skill'];
    $this->organisateurs->m_add_skill($nameSkill);
//recupearation de toutes les competences de la base pour les proposer et eviter les redondances
    $skills = $this ->organisateurs->m_show_skills();
    $this->loader->load ('schedule_add', ['title'=>'Choix des créneaux',
                                          'event'=>$event,
                                          'listTasks'=>$listTasks,
                                          'skills'=>$skills,
                                          'schedules'=>$schedules]);
  }

  public function c_missions_add($nameEvent){
    //recuperer les caractéristiques de l evenement en cours  
  $event = $this->organisateurs->m_show_event($nameEvent);    
  foreach ($_POST['Missions'] as $idTask => $skills) {
    foreach ($skills as $nameSkill => $schedules){
      foreach ($schedules as $idSchedule => $nbBenevoles){
        if ($nbBenevoles[0]!=""){
          //echo "<br>task=$idTask, skill=$nameSkill, schedule=$idSchedule, nbBenevoles=$nbBenevoles[0]<br>";
          $missions =$this->organisateurs->m_add_missions($nameSkill, $idTask, $idSchedule, $nbBenevoles[0]);
        } 
      }
    }
  }
//lancer l'affectation et afficher la page résultat    
    $this->c_possibilites($nameEvent);
  }

  public function c_possibilites($nameEvent){
//recuperer les caractéristiques de l evenement en cours
    $event = $this->organisateurs->m_show_event($nameEvent);
    $idEvent = $event[0]['idEvent'];
    $missionsEvent = $this->organisateurs->m_show_missionsEvent($idEvent);
//recuepere la liste des benevoles à la fois qualifies et dispos pour chaque mission de l'événement en cours
    foreach ($missionsEvent as $mission) {
      $nameSkill = $mission['nameSkill'];
      $idSchedule = $mission['idSchedule'];
      $idTask = $mission['idTask'];
      $possibilites = $this->organisateurs->m_list_possibles($nameSkill, $idSchedule, $idTask);
      //var_dump($possibilites);
    }   
    $this->loader->load ('pageAffectation', ['title'=>'Affectation des benevoles',
                          'event'=>$event/*,
                          'missionsEvent'=>$missionsEvent,
                          'possibilites'=>$possibilites*/]);
  }
  
  public function c_validate(){}

  private function redirect_unlogged_user() {
    if (!$this->sessions->user_is_logged()) {
      header('Location: /index.php/sessions/sessions_new');
      return true;
    }
    return false;
  }

}
