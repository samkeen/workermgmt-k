<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * This controller handles the hiring forms.
 *
 * @author     Sam Keen
 */
class Hiring_Controller extends Template_Controller {

    /**
     * No DB for this app, so these are various lookup lists needed for form
     * select list, radio groups and such
     * 
     * @var array
     */
    private $select_lists = array(
        'hire_types' => array(
            ""  => "Select ...",
            "Employee" => "Employee",
            "Intern" =>  "Intern"
        ),
        // this is retrieved from Manager_Model in actions that need it
        'managers' => array(),
        'locations' => array(
            ""  => "Select ...",
            "Mountain View" => "Mountain View",
            "Auckland" => "Auckland",
            "Beijing" => "Beijing",
            "Copenhagen" => "Copenhagen",
            "Paris" => "Paris",
            "Toronto" => "Toronto",
            "Vancouver" => "Vancouver",
            "other" => "other"
        ),
        'machine_types' => array(
            ""       => "Please select...",
            "MacBook Pro 13-inch" => "MacBook Pro 13-inch",
            "MacBook Pro 15-inch" => "MacBook Pro 15-inch",
            "Lenovo" => "Lenovo"
        ),
        'contract_types' => array('Extension'=>'Extension','New'=>'New'),
        'contract_categories' => array('Independent'=>'Independent','Corp to Corp'=>'Corp to Corp')
    );

    /**
     * Landing page
     */
    public function index() {
        $this->template->title = 'Hiring::Home';
        $this->template->content = new View('pages/hiring_home');

    }
    /**
     * Form for hiring Employee and Interns
     */
    public function employee() {
        $manager = new Manager_Model($this->get_ldap());
        $this->select_lists['managers'] = $this->format_manager_list($manager->get_list());

        $form = array(
                'hire_type' => '',
                'first_name' => '',
                'last_name' => '',
                'start_date' => '',
                'end_date' => '',
                'manager' => '',
                'buddy' => '',
                'location' => '',
                'location_other' => '',
                'mail_needed' => '',
                'default_username' => '',
                'mail_alias' => '',
                'mail_lists' => '',
                'other_comments' => '',
                'machine_needed' => '',
                'machine_type' => '',
                'machine_special_requests' => '',
        );
        $errors = $form;

        if($_POST) {
            $this->filter_disallowed_values();
            $post = new Validation($_POST);
            $post->pre_filter('trim', true);
            $post->add_rules('hire_type', 'required');
            $post->add_rules('first_name', 'required');
            $post->add_rules('last_name', 'required');
            $post->add_rules('start_date', 'required', 'valid::date');
            $post->add_rules('end_date', 'valid::date');
            if(trim($this->input->post('hire_type'))=='Intern') {
                $post->add_rules('end_date', 'required');
            }
            $post->add_rules('manager', 'required');
            $post->add_rules('location', 'required');
            if($this->input->post('location')=='other') {
                $post->add_rules('location_other', 'required');
            }          
            if($this->input->post('machine_needed')=='1') {
                $post->add_rules('machine_type', 'required');
            }
            if ($post->validate()) {
                // check for invilid
                $form = arr::overwrite($form, $post->as_array());
                $form = $this->build_supplemental_form_values($form, $manager);

                $bugs_to_file = array(Bugzilla::BUG_NEWHIRE_SETUP);
                if($form['machine_needed']) {
                    $bugs_to_file[] = Bugzilla::BUG_HARDWARE_REQUEST;
                }
                if($form['mail_needed']) {
                    $bugs_to_file[] = Bugzilla::BUG_EMAIL_SETUP;
                }
                $this->file_these($bugs_to_file, $form);

                if( ! client::has_errors()) {
                    url::redirect('hiring/employee');
                }

            } else {
                $form = arr::overwrite($form, $post->as_array());
                client::validation_results(arr::overwrite($errors, $post->errors('hiring_employee_form_validations')));
                client::messageSend("There were errors in some fields", E_USER_WARNING);
            }
        }

        $this->template->title = 'Hiring::Employee';
        $this->template->content = new View('pages/hiring_employee');
        $this->template->content->form = $form;
        $this->template->content->lists = $this->select_lists;

    }
    /**
     * Form for submitting Contractor hirings
     */
    public function contractor() {
        $manager = new Manager_Model($this->get_ldap());
        $this->select_lists['managers'] = $this->format_manager_list($manager->get_list());
        $form = array(
            'hire_type' => 'Contractor',
            'contract_type' => '',
            'contractor_category' => '',
            'first_name' => '',
            'last_name' => '',
            'org_name' => '',
            'address' => '',
            'phone_number' => '',
            'email_address' => '',
            'start_date' => '',
            'end_date' => '',
            'pay_rate'  => '',
            'payment_limit'  => '',
            'manager' => '',
            'location' => '',
            'location_other' => '',
            'statement_of_work' => '',
            'mail_needed' => '',
            'default_username' => '',
            'mail_alias' => '',
            'mail_lists' => '',
            'other_comments' => '',
                
        );
        $errors = $form;

        if($_POST) {
            $this->filter_disallowed_values();
            $post = new Validation($_POST);
            $post->pre_filter('trim', true);
            $post->add_rules('contract_type', 'required');
            $post->add_rules('contractor_category', 'required');
            $post->add_rules('first_name', 'required');
            $post->add_rules('last_name', 'required');
            $post->add_rules('org_name', 'required');
            $post->add_rules('address', 'required');
            $post->add_rules('phone_number', 'required');
            $post->add_rules('email_address', 'required');
            $post->add_rules('start_date', 'required', 'valid::date');
            $post->add_rules('end_date', 'required', 'valid::date');
            $post->add_rules('pay_rate', 'required');
            $post->add_rules('payment_limit', 'required');
            $post->add_rules('manager', 'required');
            if($this->input->post('mail_needed')=='1') {
                $post->add_rules('location', 'required');
            }
            if($this->input->post('location')=='other') {
                $post->add_rules('location_other', 'required');
            }
            $post->add_rules('statement_of_work', 'required');

            if ($post->validate()) {
                // check for invilid
                $form = arr::overwrite($form, $post->as_array());
                $form = $this->build_supplemental_form_values($form, $manager);

                $bugs_to_file = array(Bugzilla::BUG_NEWHIRE_SETUP, Bugzilla::BUG_HR_CONTRACTOR);
                if($form['mail_needed']) {
                    $bugs_to_file[] = Bugzilla::BUG_EMAIL_SETUP;
                }
                $this->file_these($bugs_to_file, $form);

                if( ! client::has_errors()) {
                    url::redirect('hiring/contractor');
                }

            } else {
                $form = arr::overwrite($form, $post->as_array());
                client::validation_results(arr::overwrite($errors, $post->errors('hiring_contractor_form_validations')));
                client::messageSend("There were errors in some fields", E_USER_WARNING);
            }

        }
        $this->template->title = 'Hiring::Contractor';
        $this->template->content = new View('pages/hiring_contractor');
        $this->template->content->form = $form;
        $this->template->content->lists = $this->select_lists;
    }
    /**
     * Foreach manager in array, builds their display name
     *  depending on what info is available.
     * 
     * @param array $managers 
     * @return array
     */
    private function format_manager_list(array $managers) {
        foreach($managers as $manager_email => &$manager_info) {
            $manager_info = !empty($manager_info['title'])
                    ? "{$manager_info['cn']} - {$manager_info['title']}"
                    : $manager_info['cn'];
        }
        return array(''=>'Select...')+$managers;
    }
    /**
     * Since we are not DB backed, need to protect the interity of these
     * lists here.
     */
    private function filter_disallowed_values() {
        switch (strtolower(Router::$method)) {
            case 'employee':
                $_POST['machine_type']=isset($_POST['machine_type'])&&key_exists(trim($_POST['machine_type']), $this->select_lists['machine_types'])
                    ? $_POST['machine_type']
                    : null;
                $_POST['hire_type']=isset($_POST['hire_type'])&&key_exists(trim($_POST['hire_type']), $this->select_lists['hire_types'])
                    ? $_POST['hire_type']
                    : null;
                break;
            case 'contractor':
                $_POST['contract_type']=isset($_POST['contract_type'])&&key_exists(trim($_POST['contract_type']), $this->select_lists['contract_types'])
                    ? $_POST['contract_type']
                    : null;
                $_POST['contractor_category']=isset($_POST['contractor_category'])&&key_exists(trim($_POST['contractor_category']), $this->select_lists['contract_categories'])
                    ? $_POST['contractor_category']
                    : null;
                break;
            default:
                break;
        }
        $_POST['manager']=isset($_POST['manager'])&&key_exists(trim($_POST['manager']), $this->select_lists['managers'])
                ? $_POST['manager']
                : null;
        $_POST['location']=isset($_POST['location'])&&key_exists(trim($_POST['location']), $this->select_lists['locations'])
                ? $_POST['location']
                : null;
    }
    /**
     * Submit these bug types using the validated from data
     * 
     * @param array $bugs_to_file Must be known values of Bugzilla
     *      i.e. Bugzilla::BUG_NEWHIRE_SETUP, Bugzilla::BUG_HR_CONTRACTOR, ...
     * @param array $form_input The validated form input
     */
    private function file_these(array $bugs_to_file, $form_input) {
        $bugzilla = Bugzilla::instance(kohana::config('workermgmt'));
        foreach ($bugs_to_file as $bug_to_file) {
            $filing = $bugzilla->newhire_filing($bug_to_file, $form_input);
            if ($filing['error_message']!==null) {
                client::messageSend($filing['error_message'], E_USER_ERROR);
            } else {
                client::messageSend($filing['success_message'], E_USER_NOTICE);
            }
        }
    }
    /**
     * Build needed additional fields for bugzilla submission
     * 
     * @param array $form The validated from input
     * @param Manager_Model $manager
     * @return $form array with additional values
     */
    private function build_supplemental_form_values(array $form, Manager_Model $manager) {
        $first_name = iconv('UTF-8', 'ASCII//TRANSLIT', $form['first_name']);
        $first_initial = iconv('UTF-8', 'ASCII//TRANSLIT', $first_name{0});
        $last_name = iconv('UTF-8', 'ASCII//TRANSLIT', $form['last_name']);
        $username = strtolower($first_initial.$last_name);
        $fullname = $first_name . " " . $last_name;

        $manager_attributes = $manager->get_attributes($form['manager']);

        return array_merge(
            $form,
            array(
            'fullname'=>$fullname,
            'username'=>$username,
            'bz_manager'=> u::arrge($manager_attributes,'bugzilla_email'),
            'manager_name' => u::arrge($manager_attributes,'cn')
            )
        );
    }
}