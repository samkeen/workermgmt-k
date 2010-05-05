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
  /**
   *
   * Available $template vars are:
   *  {{buddy name}}
   *  {{newhire_name}}
   *  {{newhire_email}}
   *  {{hiring_manager_name}}
   *
   *
   */
  public function notify_buddy($input) {
    print_r($input);die;
    if(SEND_EMAIL) {
      Kohana::config_load('workermgmt');
      $from = Kohana::config('workermgmt.email_from_address');
      $template = Kohana::config('workermgmt.buddy_email_template');
//      $to = isset($input['']) ? :;
//      $mail_result = mail(implode(", ", $notified_people), $subject, $body, "From: ". $from);
      Kohana::log('notice', $message);
    } else {
      Kohana::log('debug', "SEND_EMAIL is false so Buddy notification email not sent");
    }
  }

  
  

}