<?php
class User {
  public $loginUser;
  public $firstname;
  public $lastname;
  public $birth;
  public $mailUser;
  public $phoneUser;
  private $password;
  
  public function __construct($loginUser, $firstname , $lastname , $birth, $mailUser, $phoneUser, $password) {
    $this->loginUser = $loginUser;
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->birth = $birth;
    $this->mailUser = $mailUser;
    $this->phoneUser = $phoneUser;
    $this->password = $password;
  }
  
  public static function from_array($array) {
    return new User($array['loginUser'], $array['firstname'], $array['lastname'], $array['birth'], $array['mailUser'], $array['phoneUser'], $array['password']);
  }
  
  public function password_is_valid($password) {
    return password_verify($password, $this->password);
  }
}

class Users_model extends Model {
  const str_error_loginUser_format = 'Le login doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_firstname_format = 'Le prenom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_lastname_format = 'Le nom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_password_format = 'Le mot de passe doit contenir entre 5 et 30 caractères non blancs';
 const str_error_pseudo = 'Le pseudo choisis existe déjà';
  const str_error_mail = 'Email déjà utilisé ';
    const str_error_mdp = 'Votre mot de passe n\'est pas le même veillez revérifier';



    public function m_create_user($loginUser, $firstname , $lastname , $birth, $mailUser, $phoneUser, $password) {
    try {
        $this->check_pseudo($loginUser);
      $this->check_loginUser($loginUser);

      $this->check_firstname($firstname);
      $this->check_lastname($lastname);
      //$this->check_birth($birth);
      $this->check_mailUser($mailUser);
      //$this->check_phoneUser($phoneUser);
      $this->check_password($password);
        $this->check_mdp();

        $statement = $this->db->prepare ("INSERT INTO Users(loginUser, firstname, lastname, birth, mailUser, phoneUser, password) VALUES (:loginUser, :firstname, :lastname, :birth, :mailUser, :phoneUser, :password)");
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $statement->execute(array(
        'loginUser'=>$loginUser, 
        'firstname'=>$firstname , 
        'lastname'=>$lastname , 
        'birth'=>$birth, 
        'mailUser'=>$mailUser, 
        'phoneUser'=>$phoneUser, 
        'password'=>$hash));
      $new = new User($loginUser, $firstname , $lastname , $birth, $mailUser, $phoneUser, $hash);
      //var_dump($new);
      return $new;
    } catch (PDOException $e) {
      throw new Exception('Impossible d\'inscrire l\'utilisateur.');
    }
  }

  public function m_change_user($loginUser, $firstname , $lastname , $birth, $mailUser, $phoneUser, $password) {
    try {
      $this->check_loginUser($loginUser);
      $this->check_firstname($firstname);
      $this->check_lastname($lastname);
      //$this->check_birth($birth);
      //$this->check_mailUser($mailUser);
      //$this->check_phoneUser($phoneUser);
      $statement = $this->db->prepare ("UPDATE Users
                                        SET firstname = :firstname, 
                                            lastname = :lastname, 
                                            birth = :birth, 
                                            mailUser = :mailUser, 
                                            phoneUser = :phoneUser, 
                                            password = :password
                                        WHERE loginUser = :loginUser");
      $hash = password_hash($password, PASSWORD_DEFAULT);
    //  if (false === $statement) {
    //    echo sprintf("tutu");
    // }
      $statement->execute(array(
        'firstname'=>$firstname , 
        'lastname'=>$lastname , 
        'birth'=>$birth, 
        'mailUser'=>$mailUser, 
        'phoneUser'=>$phoneUser, 
        'password'=>$hash,
        'loginUser'=>$loginUser
      ));
      //$change = $statement->fetchAll();
      //var_dump($change);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception('Impossible de modifier l\'utilisateur.');
    }
  }
  
  public function user_from_loginUser($loginUser) {
    $this->check_loginUser($loginUser);
    return $this->user_from_query('SELECT * FROM Users WHERE loginUser = ?', [$loginUser]);
  }

  private function check_loginUser($loginUser) {
    $this->check_format_with_regex($loginUser, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_loginUser_format);
  }

  private function check_firstname($firstname) {
    $this->check_format_with_regex($firstname, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_firstname_format);
  }

  private function check_lastname($lastname) {
    $this->check_format_with_regex($lastname, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_lastname_format);
  }

  private function check_password($password) {
    $this->check_format_with_regex($password, '/^[^\s]{5,10}$/', self::str_error_password_format);
  }

  //   private function check_birth($birth) {
  //   $this->;
  // }

  //   private function check_mailUser($mailUser) {
  //   $this->check_format_with_regex($mailUser, );
  // }

  //   private function check_phoneUser($phoneUser) {
  //   $this->check_format_with_regex($phoneUser, );
  // }
  
 private function check_pseudo ($pseud)
  {
    $this->check_pseudo_existe($pseud, self::str_error_pseudo);

  }
  private function check_pseudo_existe ($pseud, $error_message)
  {

    $req= $this->db->prepare("SELECT * FROM Users WHERE loginUser = ?");
    $req->execute(array($pseud));
    $pseudoexist = $req->fetch();
    if ($pseudoexist)
    {
      throw new Exception ($error_message);
    }
  }

  private function check_mailUser ($mail) {
    $this->check_mailUser_existe ($mail, self::str_error_mail);
  }
  private function check_mailUser_existe ($mailUser,$error_message)
  {
    $req= $this->db->prepare("SELECT * FROM Users WHERE mailUser = ?");
    $req->execute(array($mailUser));
    $mailexist = $req->fetch();
    if ($mailexist )
    {
      throw new Exception($error_message);
    }
  }

  private function check_mdp ()
    {
        $this->check_mdpIdentique (self:: str_error_mdp);

    }
    private function check_mdpIdentique ($error_message)
    {
        if($_POST['password'] != $_POST['passwordCong'])
            {
                throw new Exception($error_message);
            }
    }

  
  private function user_from_query($query, $array) {
    try {
      $statement = $this->db->prepare($query);
      $statement->execute($array);
      $users = $statement->fetchAll();
      if (count($users)==0) return null;
      return User::from_array($users[0]);
    } catch (PDOException $e) {
      throw new Exception('Impossible d\'effectuer la demande.');
    }
  }

  private function check_format_with_regex($variable, $regex, $error_message) {
    $result = filter_var ( $variable, FILTER_VALIDATE_REGEXP, array (
        'options' => array (
            'regexp' => $regex
        )
    ) );
    if ($result === false || $result === null) {
      throw new Exception ( $error_message );
    }
  }

  public function m_show_user($logged_user){
    try {
      $statement = $this->db->prepare("SELECT * FROM Users WHERE loginUser = :loginUser");
      $statement->execute (['loginUser' => $logged_user]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_benevole_historique($logged_user){
    try {
      $statement = $this->db->prepare("SELECT * FROM Affectation WHERE loginUser = :loginUser");
      $statement->execute (['loginUser' => $logged_user]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

  public function m_organisateur_historique($logged_user){
    try {
      $statement = $this->db->prepare("SELECT * FROM Event WHERE loginUser = :loginUser");
      $statement->execute (['loginUser' => $logged_user]);
      return $statement->fetchAll();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }


}



