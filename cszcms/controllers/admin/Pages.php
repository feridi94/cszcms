<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends CI_Controller {

    function __construct() {
        parent::__construct();
        define('LANG', $this->Csz_admin_model->getLang());
        $this->lang->load('admin', LANG);
        $this->template->set_template('admin');
        $this->_init();
    }

    public function _init() {
        $row = $this->Csz_admin_model->load_config();
        $pageURL = $this->Csz_admin_model->getCurPages();
        $this->template->set('core_css', $this->Csz_admin_model->coreCss());
        $this->template->set('core_js', $this->Csz_admin_model->coreJs());
        $this->template->set('title', 'Backend System | ' . $row->site_name);
        $this->template->set('meta_tags', $this->Csz_admin_model->coreMetatags('Backend System for CSZ Content Management'));
        $this->template->set('cur_page', $pageURL);
    }

    public function index() {
        admin_helper::is_logged_in($this->session->userdata('admin_email'));
        $this->load->library('pagination');
        $this->csz_referrer->setIndex();

        // Pages variable
        $result_per_page = 20;
        $total_row = $this->Csz_admin_model->countTable('pages');
        $num_link = 10;
        $base_url = BASE_URL . '/admin/pages/';

        // Pageination config
        $this->Csz_admin_model->pageSetting($base_url,$total_row,$result_per_page,$num_link);    
        ($this->uri->segment(3))? $pagination = ($this->uri->segment(3)) : $pagination = 0;        

        //Get users from database
        $this->template->setSub('pages', $this->Csz_admin_model->getIndexData('pages', $result_per_page, $pagination, 'pages_id', 'asc'));

        //Load the view
        $this->template->loadSub('admin/pages_index');
    }

    public function addPages() {
        admin_helper::is_logged_in($this->session->userdata('admin_email'));
        
        //Get lang from database
        $this->template->setSub('lang', $this->Csz_model->loadAllLang());
        
        //Load the form helper
        $this->load->helper('form');
        //Load the view
        $this->template->loadSub('admin/pages_add');
    }

    public function insert() {
        admin_helper::is_logged_in($this->session->userdata('admin_email'));
        //Load the form validation library
        $this->load->library('form_validation');
        //Set validation rules
        $this->form_validation->set_rules('page_name', 'Pages Name', 'required');
        $this->form_validation->set_rules('page_title', 'Pages Title', 'required');
        $this->form_validation->set_rules('page_keywords', 'Pages Keywords', 'required');
        $this->form_validation->set_rules('page_desc', 'Pages Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            //Validation failed
            $this->addPages();
        } else {
            //Validation passed
            //Add the user
            $this->Csz_admin_model->insertPage();
            //Return to user list
            redirect($this->csz_referrer->getIndex(), 'refresh');
        }
    }

    public function editPages() {
        admin_helper::is_logged_in($this->session->userdata('admin_email'));
        //Load the form helper
        $this->load->helper('form');
        if($this->uri->segment(4)){
            //Get user details from database
            //Get lang from database
            $this->template->setSub('lang', $this->Csz_model->loadAllLang());
            $this->template->setSub('pages', $this->Csz_model->getValue('*', 'pages', 'pages_id', $this->uri->segment(4), 1));
            //Load the view
            $this->template->loadSub('admin/pages_edit');
        }else{
            redirect($this->csz_referrer->getIndex(), 'refresh');
        }
    }

    public function edited() {
        admin_helper::is_logged_in($this->session->userdata('admin_email'));
        //Load the form validation library
        $this->load->library('form_validation');
        //Set validation rules
        $this->form_validation->set_rules('page_name', 'Pages Name', 'required');
        $this->form_validation->set_rules('page_title', 'Pages Title', 'required');
        $this->form_validation->set_rules('page_keywords', 'Pages Keywords', 'required');
        $this->form_validation->set_rules('page_desc', 'Pages Description', 'required');

        if ($this->form_validation->run() == FALSE) {
            //Validation failed
            $this->editPages();
        } else {
            //Validation passed
            //Update the user
            $this->Csz_admin_model->updatePage($this->uri->segment(4));
            //Return to user list
            redirect($this->csz_referrer->getIndex(), 'refresh');
        }
    }

    public function delete() {
        admin_helper::is_logged_in($this->session->userdata('admin_email'));
        if($this->uri->segment(4)){
            //Delete the languages
            if($this->uri->segment(4) != 1) {
                $this->Csz_model->clear_all_cache();
                $this->Csz_admin_model->removeData('pages', 'pages_id', $this->uri->segment(4));
            } else {
                echo "<script>alert(\"" . $this->lang->line('pages_delete_default') . "\");</script>";
            }
        }
        //Return to languages list
        redirect($this->csz_referrer->getIndex(), 'refresh');
    }
}
