<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['bugzilla_url'] = '';
$config['debug_logging'] = true;
$config['ldap_anon_bind'] = '';
$config['ldap_anon_password'] = '';
$config['ldap_host'] = '';
$config['ldap_base_dn'] = '';
/**
 * use_mock_ldap
 * The app in PRODUCTION grabs the ldap username and passwd from the
 * LDAP backed HTTPAuth.
 * If you are testing and not behind this sort of setup, use MockLdap
 * to proxy the 2 LDAP calls and return test data.
 *
 *  - manager_list() Returns the list of manager to populatet the manager
 *    select list in the hiring forms
 *  - manager_attributes() At this point only used in hiring forms to get the
 *    cn and bugzilla email for a given managers email
 *
 * @see lib/MockLdap
 * The app will NOT allow 'use_mock_ldap' to be turned on for IN_PRODUCTION
 */
$config['use_mock_ldap'] = false;


