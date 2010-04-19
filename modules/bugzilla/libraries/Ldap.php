<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Ldap library.
 *
 *
 * @package    Ldap
 * @author     skeen@mozilla.org
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Ldap_Core {

  private $ds = null;// currently pub, called in newhire
  private $user_dn = null;

  private $successfully_bound = false;

  private $host = null;
  private $anon_bind = null;
  private $anon_password = null;
  private $base_dn = null;

  private $cache_ttl = 0;

  /**
   *
   * @param array $config
   * @param array $credentials array('username'=>'...', 'password'=>'...')
   */
  public function  __construct(array $config, array $credentials) {
    $this->credentials = $credentials;
    $this->host = isset($config['ldap_host'])?$config['ldap_host']:null;
    $this->anon_bind = isset($config['ldap_anon_bind'])?$config['ldap_anon_bind']:null;
    $this->anon_password = isset($config['ldap_anon_password'])?$config['ldap_anon_password']:null;
    $this->base_dn = isset($config['ldap_base_dn'])?$config['ldap_base_dn']:null;
    $this->cache_ttl = isset($config['ldap_cache_ttl'])?$config['ldap_cache_ttl']:null;
    
  }
  public function  __destruct() {
    if($this->ds) {
      ldap_close($this->ds);
    }
  }
  /**
   * Get all mozComPerson's from LDAP where
   * isManager=TRUE AND employeetype!=DISABLED
   *
   * @return array in the form:
   * Array(
   *  [morgamic@mozilla.com] => Array
        (
            [cn] => Mike Morgan
            [title] => Director of Web Development
            [bugzilla_email] => morgamic@gmail.com
        )
   *  , ...
   * )
   */
  public function manager_list() {
    $this->bind_as_user();
    $manager_list = null;
    $manager_search = $this->ldap_search(
      '(&(objectClass=mozComPerson)(isManager=TRUE)(!(employeetype=DISABLED)))'
      ,array("mail","employeetype","bugzillaEmail","cn","title")
    );

    if($manager_search) {
      ldap_sort($this->ds(), $manager_search, 'cn');
      $manager_list = ldap_get_entries($this->ds(), $manager_search);
    } else {
      kohana::log('error',"LDAP search failed using [{$this->ds()}, {$this->base_dn}, "
        ."(&(objectClass=mozComPerson)(isManager=TRUE)(!(employeetype=DISABLED)))]"
        ."LDAP error:[".ldap_error($this->ds)."]");
    }
    $manager_list = $this->flatten_ldap_results($manager_list);
    
    $cleaned_list = array();
    foreach ($manager_list as $manager) {
      // ensure keys to keep out of isset?:;
      $manager = array_merge(array('cn'=>null,'title'=>null,'mail'=>null,'bugzillaemail'=>null),$manager);
      
      if(! empty($manager['mail'])) {
        $bugzilla_email = !empty($manager['bugzillaemail'])
          ?$manager['bugzillaemail']
          :$manager['mail'];
        $cleaned_list[$manager['mail']] = array(
          'cn' => $manager['cn']?$manager['cn']:null,
          'title' => $manager['title']?$manager['title']:null,
          'bugzilla_email' => $bugzilla_email
        );
      }
    }
    return $cleaned_list;
  }
  /**
   * Returns LDAP attributes for the given email
   * @param string $ldap_email
   * @return array
   */
  public function manager_attributes($ldap_email) {
    $manager = null;
    $manager = $this->fetch_user_array($ldap_email, array("mail","employeetype","bugzillaEmail","cn","title"));
    return isset($manager[0])?$manager[0]:array();
  }

  /**
   * Allows for lazy binding based on $this->successfully_bound
   * Call at the start of any method that needs an LDAP binding
   *
   * @return boolean success of binding
   */
  private function bind_as_user() {
    if($this->successfully_bound) {return true;}
    $bind_successful = false;
    kohana::log('debug', "Attempting: \$this->init_dn_from_username({$this->credentials['username']})");
    if($this->init_dn_from_username($this->credentials['username'])) {
      kohana::log('debug', "ldap_bind(..., {$this->user_dn} , ...)");
      if( ! ldap_bind($this->ds(),$this->user_dn, $this->credentials['password'])) {
        kohana::log('error',"Failed To Bind to LDAP with user DN[{$this->user_dn}].\n"
          ."LDAP error:[".ldap_error($this->ds())."]");
        $this->successfully_bound = false;
      } else {
        $this->successfully_bound = true;
        kohana::log('debug',"Successfully bound as user: [{$this->user_dn}]");
      }
    }
    return $bind_successful;
  }
  /**
   * Bind anonymous and return the DN for the given $username
   * @param string $username
   */
  private function init_dn_from_username($username) {

      

    $success = false;
    if (! $this->user_dn) {
      kohana::log('debug',"Atempting (anonymous) ldap_bind(\$this->ds(), '{$this->anon_bind}', #password#)");
      if( ! ldap_bind($this->ds(), $this->anon_bind, $this->anon_password)) {
        kohana::log('error',"Failed Anon Bind to LDAP using [".$this->anon_bind."]\n"
                ."LDAP error:[".ldap_error($this->ds)."]");
      } else {
        $search = $this->ldap_search("mail=$username");
        
        $search_results = ldap_get_entries($this->ds(),$search);
        
        if($search_results['count'] != 1) {
            $success = false;
        } else {
            // @TODO apply filter here
            $this->user_dn = $search_results[0]['dn'];
            $success = true;
        }
      }
    }
    return $success;
  }

  /**
   * Wrap this in a method to allow fo rlazy connections
   * 
   * @return resource LDAP connection
   */
  private function ds() {
    if( ! $this->ds) {
      $this->ds = ldap_connect($this->host);
      if(!$this->ds) {
        kohana::log('error',"FAILED to connect to LDAP host [{$this->host}]");
      }
      kohana::log('debug', "Successfully connected to LDAP [{$this->host}]");
    }
    return $this->ds;
  }
  /**
   * Returns to specified attributes for a given user
   * 
   * @param string $ldap_email
   * @param array $attrbutes_to_return Optional, defaults to "*"
   * @return array
   */
  private function fetch_user_array($ldap_email, $attrbutes_to_return=array("*")) {
    $this->bind_as_user();
    $search_results = array();
    $search = ldap_search("mail=$ldap_email",$attrbutes_to_return);
    if($search) {
      $search_results = ldap_get_entries($this->ds(),$search);
      $search_results = $this->flatten_ldap_results($search_results);
    } else {
      kohana::log('error', "LDAP search failed using [{$this->ds()},{$this->base_dn}, "
        ."(&(objectClass=mozComPerson)(isManager=TRUE))]"
        ."LDAP error:[".ldap_error($this->ds)."]");
    }
    return $search_results;
  }
  /**
   * Array results that come back form ldap_get_entries() are whacked
   *
   * @param array $ldap_result_array The array that comes from
   * ldap_get_entries()
   *
   * @return array
   */
  private function flatten_ldap_results(array $ldap_result_array) {
    $ldap_result_array = is_array($ldap_result_array)?$ldap_result_array:array();
    unset($ldap_result_array['count']);
    foreach ($ldap_result_array as &$ldap_result) {
      foreach ($ldap_result as $index => &$result) {
        unset($ldap_result['count']);
        if(is_int($index)) {
          unset($ldap_result[$index]);
          continue;
        }
        if(is_array($result)) {
          if(isset ($result['count'])&&$result['count']==1) {
            $result = $result[0];
          } else if(isset ($result['count'])&&$result['count']>1) {
            unset ($result['count']);
          }
        }
      }
    }
    return $ldap_result_array;
  }

  
  /**
   * centralizing the search method so we can apply consistent LDAP Injection
   * filtering
   */
  private function ldap_search($search_filter, array $attributes_to_return=null) {
    kohana::log('debug',"Attempting ldap_search whith dn:'{$this->base_dn}' and filter:'{$search_filter}'");
    if($attributes_to_return) {
        return ldap_search($this->ds(),$this->base_dn,$search_filter, $attributes_to_return);
    } else {
        return ldap_search($this->ds(),$this->base_dn,$search_filter);
    }
    
  }

}