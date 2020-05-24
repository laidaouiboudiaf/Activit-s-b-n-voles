<?php
class Benevoles_model extends Model {
  
  const str_error_album_name_format = 'Le nom d\'un album doit commencer par une lettre et contenir uniquement des lettres, des chiffres et des espaces.';
  const str_error_photo_name_format = 'Le nom d\'une photo doit commencer par une lettre et contenir uniquement des lettres, des chiffres et des espaces.';
  const str_error_album_does_not_exist = 'L\'album n\'existe pas.';
  const str_error_photo_does_not_exist = 'La photo n\'existe pas.';
  const str_error_photo_format = 'La photo n\'a pas pu être sauvegardée.';
  const str_error_database = 'Problème avec la base de données.';
  
  // public function albums() {
  //   try {
  //     $statement = $this->db->prepare("select * from albums");
  //     $statement->execute();
  //     return $statement->fetchAll();
  //   } catch (PDOException $e) {
  //     throw new Exception(self::str_error_database);
  //   }
  // }
    public function m_showEvents() {
    try {
      $statement = $this->db->query("select * from Event");
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_listFutursEvents() {
    try {
      $statement = $this->db->prepare("SELECT * FROM Event WHERE deadline > CURRENT_DATE");
      $statement->execute();
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_show_skill($nameEvent){
    try {
      $statement = $this->db->prepare("SELECT DISTINCT nameSkill, nameEvent FROM Composition C JOIN missions M ON C. idTask = M. idTask WHERE nameEvent = :nameEvent");
      $statement->execute(
        ['nameEvent'=> $nameEvent]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_add_skill($skill, $loginUser){
    $statement = $this->db->prepare ("INSERT INTO Qualification(loginUser, nameSkill) VALUES (:loginUser, :nameSkill)");
    $statement->execute(array(
        'loginUser'=>$loginUser,
        'nameSkill'=>$skill));
    $statement->fetchAll();
  }

  public function m_show_schedule(){
      try {
      $statement = $this->db->query("SELECT DISTINCT idSchedule, hDeb, hFin FROM Schedule");
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_add_schedule($dispo, $loginUser){
    $statement = $this->db->prepare ("INSERT INTO Disponibilite(loginUser, idSchedule) VALUES (:loginUser, :idSchedule)");
    $statement->execute(array(
        'loginUser'=>$loginUser,
        'idSchedule'=>$dispo));
    $statement->fetchAll();
  }

}
