<?php defined('SYSPATH') or die('No direct script access.');

class Hiring_Model {

  

  /**
   * Class constructor.
   *
   * @access	public
   * @return	void
   */
  public function __construct(Ldap_Core $ldap) {
    $this->ldap = $ldap;
  }

  public function manager_list() {
      return $this->ldap->employee_list('manager');
  }
  public function buddy_list() {
      return $this->ldap->employee_list('all');
  }
  public function employee_attributes($ldap_email) {
      return $this->ldap->employee_attributes($ldap_email);
  }

  
  

}