<?php
class Organisateurs_model extends Model {
  
  const str_error_album_name_format = 'Le nom d\'un album doit commencer par une lettre et contenir uniquement des lettres, des chiffres et des espaces.';
  const str_error_photo_name_format = 'Le nom d\'une photo doit commencer par une lettre et contenir uniquement des lettres, des chiffres et des espaces.';
  const str_error_album_does_not_exist = 'L\'album n\'existe pas.';
  const str_error_photo_does_not_exist = 'La photo n\'existe pas.';
  const str_error_photo_format = 'La photo n\'a pas pu être sauvegardée.';
  const str_error_database = 'Problème avec la base de données.';
  

  public function m_listPastsEvents($logged_user) {  
    try {
      $statement = $this->db->prepare("SELECT * FROM Event WHERE loginUser = :loginUser AND dateEvent < CURRENT_DATE ");
      $statement->execute (['loginUser' => $logged_user]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_listFutursEvents($logged_user) {
    try {
      $statement = $this->db->prepare("SELECT * FROM Event WHERE loginUser = :loginUser AND deadline > CURRENT_DATE");
      $statement->execute (['loginUser' => $logged_user]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_create_event($nameEvent, $dateEvent, $deadline, $typeEvent, $descriptionEvent, $road, $mailEvent, $website, $picture, $CP, $loginUser){
    try{
      $statement = $this->db->prepare ("INSERT INTO Event(nameEvent,  dateEvent, deadline, typeEvent, descriptionEvent, road, mailEvent, website, picture, CP, loginUser) VALUES (:nameEvent, :dateEvent, :deadline, :typeEvent, :descriptionEvent, :road, :mailEvent, :website, :picture, :CP, :loginUser)");
      $statement->execute(array(
        'nameEvent'=>$nameEvent,
        'dateEvent'=> $dateEvent,
        'deadline'=> $deadline, 
        'typeEvent'=> $typeEvent,
        'descriptionEvent'=> $descriptionEvent,
        'road'=> $road, 
        'mailEvent'=> $mailEvent,
        'website'=> $website, 
        'picture'=> $picture, 
        'CP'=> $CP, 
        'loginUser'=> $loginUser));
      $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception('Impossible de créer le nouvel événement');
    }
  }

  public function m_show_event($nameEvent){
    try {
      $statement = $this->db->query("SELECT * FROM Event WHERE nameEvent = :nameEvent");
          $statement->execute(['nameEvent'=>$nameEvent]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }
  
  public function m_change_event ($idEvent){echo"methode m_change_event pas codee";}

  public function m_add_task($nameTask){
    $statement = $this->db->prepare ("INSERT INTO Task(nameTask) VALUES (:nameTask)");
    $statement->execute(array(
        'nameTask'=>$nameTask));
    $statement->fetchAll();
  }

  public function m_show_tasks() {
    try {
      $statement = $this->db->query("SELECT idTask, nameTask FROM Task");
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_add_composition($idEvent, $nameEvent, $idTask, $nameTask, $nameResp, $phoneResp){
    $statement = $this->db->prepare ("INSERT INTO Composition(idEvent, nameEvent, idTask, nameTask, nameResp, phoneResp) VALUES (:idEvent, :nameEvent, :idTask, :nameTask, :nameResp, :phoneResp)");
    $statement->execute(array(
        'idEvent'=>$idEvent,
        'nameEvent'=>$nameEvent, 
        'idTask'=>$idTask,
        'nameTask'=>$nameTask,
        'nameResp' =>$nameResp,
        'phoneResp'=> $phoneResp
       ));
    $statement->fetchAll();
  }

  public function m_show_tasksEvent($nameEvent) {
    try {
      $statement = $this->db->prepare("SELECT idTask, nameTask FROM Composition WHERE nameEvent = :nameEvent");
      $statement ->execute(array(
        'nameEvent'=>$nameEvent));
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_show_schedulesTask($idTask) {
    try {
      $statement = $this->db->prepare("SELECT hDeb, hFin FROM Schedule S JOIN Missions M ON S. idSchedule = M. idSchedule WHERE idTask = :idTask");
      $statement ->execute(array(
        'idTask'=>$idTask));
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_show_schedules() {
    try {
      $statement = $this->db->query("SELECT DISTINCT idSchedule, hDeb, hFin FROM Schedule ORDER BY hDeb ASC");
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }
  
  public function m_add_schedule($hDeb, $hFin){
    $statement = $this->db->prepare ("INSERT INTO Schedule(hDeb, hFin) VALUES (:hDeb, :hFin)");
    $statement->execute(array(
        'hDeb'=>$hDeb,
        'hFin'=>$hFin)); 
    $statement->fetchAll();
  }

  public function m_add_skill($nameSkill){
    $statement = $this->db->prepare ("INSERT INTO Skill(nameSkill) VALUES (:nameSkill)");
    $statement->execute(array(
        'nameSkill'=>$nameSkill)); 
    $statement->fetchAll();
  }

  public function m_show_skills() {
    try {
      $statement = $this->db->query("SELECT DISTINCT nameSkill FROM Skill");
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_add_missions($nameSkill, $idTask, $idSchedule, $nbBenevoles){
    $statement = $this->db->prepare ("INSERT INTO Missions(nameSkill, idTask, idSchedule, nbBenevoles) VALUES (:nameSkill, :idTask, :idSchedule, :nbBenevoles)");
    $statement->execute(array(
        'nameSkill'=>$nameSkill, 
        'idTask'=>$idTask,
        'idSchedule'=>$idSchedule,
        'nbBenevoles' =>$nbBenevoles
       ));
    $statement->fetchAll();
  }

  public function m_show_missionsEvent($idEvent){
    $statement = $this->db->prepare("SELECT nameEvent, M. idTask, idSchedule, nameSkill, nbBenevoles
                                      FROM Composition C JOIN Missions M ON C. idTask = M. idTask
                                      WHERE idEvent = :idEvent");
    $statement->execute(array(
        'idEvent'=>$idEvent));
    return $statement->fetchAll();
  }

  public function m_list_possibles($nameSkill, $idSchedule, $idTask){
    $statement = $this->db->prepare("SELECT DISTINCT loginUser
                                      FROM DispoPourMission
                                      WHERE idTask=:idTask AND idSchedule=:idSchedule
                                      INTERSECT 
                                      SELECT DISTINCT loginUser
                                      FROM CompetentPourMission
                                      WHERE idTask=:idTask AND nameSkill= :nameSkill");
    $statement->execute(array(
        'idTask'=>$idTask,
        'idSchedule'=>$idSchedule,
        'nameSkill'=> $nameSkill));
    return $statement->fetchAll();
  }


}
