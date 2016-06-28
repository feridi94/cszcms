<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navigation extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();               
		define('LANG', $this->Csz_admin_model->getLang());
		$this->lang->load('admin', LANG);
                $this->template->set_template('admin');
                $this->_init();
	}
        
        public function _init() {
            $row = $this->Csz_admin_model->load_config();
            $totSegments = $this->uri->total_segments();
            $pageURL = $this->Csz_admin_model->getCurPages();
            $this->template->set('core_css', $this->Csz_admin_model->coreCss());
            $this->template->set('core_js', $this->Csz_admin_model->coreJs());
            $this->template->set('title', 'Backend System | ' . $row->site_name);
            $this->template->set('meta_tags', $this->Csz_admin_model->coreMetatags('Backend System for CSZ Content Management'));
            $this->template->set('cur_page', $pageURL);
        }
	
	public function index(){
		admin_helper::is_logged_in($this->session->userdata('admin_email'));
                $this->csz_referrer->setIndex();
                $this->template->set('cur_page', $this->uri->segment(2));
                if(!$this->uri->segment(3)){
                    $lang = $this->Csz_model->getDefualtLang();
                }else{
                    $lang = $this->uri->segment(3);
                }
		//Get menu from database
                $this->template->setSub('nav', $this->Csz_admin_model->getAllMenu('',$lang));
                $this->template->setSub('lang', $this->Csz_model->loadAllLang());
		$this->load->helper('form');
		//Load the view
                $this->template->loadSub('admin/nav_index');
	}
        
        public function saveNav(){
                admin_helper::is_logged_in($this->session->userdata('admin_email'));
                $this->Csz_admin_model->sortNav();
                redirect($this->csz_referrer->getIndex(), 'refresh');
        }


        public function newNav(){
		admin_helper::is_logged_in($this->session->userdata('admin_email'));
		//Get pages from database
                $this->template->setSub('pages', $this->Csz_admin_model->getPagesAll());
                $this->template->setSub('dropmenu', $this->Csz_admin_model->getDropMenuAll());
                $this->template->setSub('lang', $this->Csz_model->loadAllLang());
		$this->load->helper('form');
		//Load the view
                $this->template->loadSub('admin/nav_add');
	}
        
        public function insert()
	{
		admin_helper::is_logged_in($this->session->userdata('admin_email'));
		//Load the form validation library
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', $this->lang->line('navpage_menuname'), 'trim|required');
		
		if($this->form_validation->run() == FALSE) {
			//Validation failed
			$this->newNav();
		}  else  {
			//Validation passed
			$this->Csz_admin_model->insertMenu();
			//Return to navigation list
			redirect($this->csz_referrer->getIndex(), 'refresh');
	  	}
		
	}	
	
	public function editNav()
	{
		admin_helper::is_logged_in($this->session->userdata('admin_email'));
                if($this->uri->segment(4)){
                    //Get pages from database
                    $this->template->setSub('pages', $this->Csz_admin_model->getPagesAll());
                    $this->template->setSub('dropmenu', $this->Csz_admin_model->getDropMenuAll());
                    $this->template->setSub('lang', $this->Csz_model->loadAllLang());
                    //Get navigation from database
                    $this->template->setSub('nav', $this->Csz_model->getValue('*', 'page_menu', 'page_menu_id', $this->uri->segment(4), 1));
                    $this->load->helper('form');
                    //Load the view
                    $this->template->loadSub('admin/nav_edit');
                }else{
                    redirect($this->csz_referrer->getIndex(), 'refresh');
                }
	}
	
	public function update()
	{
		admin_helper::is_logged_in($this->session->userdata('admin_email'));
		//Load the form validation library
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', $this->lang->line('navpage_menuname'), 'trim|required');
		
		if($this->form_validation->run() == FALSE) {
			//Validation failed
			$this->editNav();
		}  else  {
			//Validation passed
			$this->Csz_admin_model->updateMenu($this->uri->segment(4));
			//Return to navigation list
			redirect($this->csz_referrer->getIndex(), 'refresh');
	  	}
	}
	
        public function deleteNav() {
            admin_helper::is_logged_in($this->session->userdata('admin_email'));
            if($this->uri->segment(4)){
                //Delete the user account
                $this->Csz_admin_model->removeData('page_menu','page_menu_id',$this->uri->segment(4));
                $this->Csz_admin_model->removeData('page_menu','drop_page_menu_id',$this->uri->segment(4));
            }
            //Return to user list
            redirect($this->csz_referrer->getIndex(), 'refresh');
        }
}
