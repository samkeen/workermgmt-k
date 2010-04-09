<?php defined('SYSPATH') or die('No direct script access.');

class Manager_Model {

  

  /**
   * Class constructor.
   *
   * @access	public
   * @return	void
   */
  public function __construct(Ldap_Core $ldap) {

    $this->ldap = $ldap;
  }

  public function get_list() {
      return $this->ldap->manager_list();;
  }
  public function get_attributes($manager) {
      return $this->ldap->manager_attributes($manager);;
  }

  
  

}