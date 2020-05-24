<?php
class Users extends Controller {

  public function index() {
    $this->loader->load();
  }
  
  public function users_new() {
    $this->loader->load('users_new', ['title'=>'S\'inscrire']);
  }
  
  public function c_users_create() {
    try {
      $loginUser = filter_input(INPUT_POST, 'loginUser');
      $firstname = filter_input(INPUT_POST, 'firstname');
      $lastname = filter_input(INPUT_POST, 'lastname');
      $birth = filter_input(INPUT_POST, 'birth');
      $mailUser = filter_input(INPUT_POST, 'mailUser');
      $phoneUser = filter_input(INPUT_POST, 'phoneUser');
      $password = filter_input(INPUT_POST, 'password');
      //$passwordConf = filter_input(INPUT_POST, 'passwordConf');
      $user = $this->users->m_create_user($loginUser, $firstname , $lastname , $birth, $mailUser, $phoneUser, $password);
      $this->sessions->login($user);
      $this->loader->load('moncompte', ['title'=>'Mon compte']);
    } catch (Exception $e) {
      $data = ['error' => $e->getMessage(), 'title'=>'S\'inscrire'];
      $this->loader->load('users_new', $data );
    }
  }

  public function c_compte_show($logged_user){
    $this->loader->load('moncompte', ['title'=>'Mon compte']);
  }

  public function c_user_show($logged_user){
    $this->loader->load('user_show', ['title'=>'Mes informations personnelles',
                                      'infosUser'=>$this->users->m_show_user($logged_user),
                                    ]);
  }

  public function c_histo_show($logged_user){
    //var_dump($logged_user);
    $histo_bene = $this->users->m_benevole_historique($logged_user);
    $histo_org = $this->users->m_organisateur_historique($logged_user);
    $this->loader->load('user_histo', ['title'=>'Mon historique',
                                      'histo_bene'=>$histo_bene,
                                      'histo_org'=>$histo_org]);
  }

  public function c_new_form($logged_user) {
    $this->loader->load('user_change', ['title'=>'Modification des informations personnelles']);
  }

  public function c_users_change() {
    try {
      $loginUser = filter_input(INPUT_POST, 'loginUser');
      $firstname = filter_input(INPUT_POST, 'firstname');
      $lastname = filter_input(INPUT_POST, 'lastname');
      $birth = filter_input(INPUT_POST, 'birth');
      $mailUser = filter_input(INPUT_POST, 'mailUser');
      $phoneUser = filter_input(INPUT_POST, 'phoneUser');
      $password = filter_input(INPUT_POST, 'password');
      //$passwordConf = filter_input(INPUT_POST, 'passwordConf');
      $user = $this->users->m_change_user($loginUser, $firstname , $lastname , $birth, $mailUser, $phoneUser, $password);
      var_dump($user);
      //$this->sessions->login($user);
      $this->loader->load('moncompte', ['title'=>'Mon compte', 'user'=>$user]);
    } catch (Exception $e) {
      $data = ['error' => $e->getMessage(), 'title'=>'Modifier ne MARCHE PAS !'];
      $this->loader->load('user_change', $data );
    }
  }

}