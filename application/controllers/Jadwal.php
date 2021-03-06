<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends MY_Controller {
	
	function __construct() {
        parent::__construct();
        $this->load->model("Table_Jadwal", "jdwl");
        $this->load->model("Table_Olahraga", "olg");
    }

	public function index()
	{
		if($this->_check_func($this)){
			$m = $this->method;
			$this->$m();
		}else{
			$this->_api(JSON_ERROR, "tidak ada method ".$this->method." di class record");
		}
	}

	public function get_jadwal(){
		$jadwalCode =   $this->post('id_jadwal');
		if ($jadwalCode != "") {
            $jadwal = $this->jdwl->get(array(
                    'id_jadwal'     => $jadwalCode,
                    'id_user'       => $this->post('id_user')
                ));
        }else{
            $jadwal = $this->jdwl->get(array(                    
                    'id_user'       => $this->post('id_user')
                ));
        }
        $res = array();
        foreach ($jadwal as $key) {
            $nama = $this->olg->get($key->id_olahraga, 'nama_olahraga');
            $kkal = $this->olg->get($key->id_olahraga, 'kkal');
            $res[] = array( 
            	"id_jadwal"		=> $key->id_jadwal,
                "id_user"		=> $key->id_user,
                "id_olahraga"	=> $key->id_olahraga,               
                "tanggal"		=> $key->tanggal,
                "kalori"		=> $key->kalori,
                "nama_olahraga" => (is_object($nama[0])) ? $nama[0]->nama_olahraga : 0,
                "kal_olahraga"  => (is_object($kkal[0])) ? $kkal[0]->kkal : 0
            );
        }
        $this->_api(JSON_SUCCESS, "Success Get Data Record Olahraga", $res);
	}

    public function insertJadwal(){
        $kal = $this->olg->get($this->post('id_olahraga'), 'kkal');
        $data = array(
        	//'id_recordolg'		=> $this->post('id_recordolg'),
        	//'id_jadwal'			=> $this->post('id_jadwal'),
            'id_user'			=> $this->post('id_user'),
            'tanggal'			=> $this->post('tanggal'),
            'id_olahraga'		=> $this->post('id_olahraga'),
            'kalori'            => $this->post('kalori')
            //'kalori'			=> (is_object($kal[0])) ? $kal[0]->kkal : 0
        );
        $insert = $this->jdwl->insert($data);
        if ($insert) {
        	$this->_api(JSON_SUCCESS, "Success Insert Data", $data);                        
        } else {
            $this->_api(JSON_ERROR, "Insert Data Gagal", $data);
       	}
    }    
    
    public function delete(){                
        $delete = $this->jdwl->delete($this->post("id_jadwal"));
        if ($delete) {            
            $this->_api(JSON_SUCCESS, "Success Delete Data");
        } else {
            $this->_api(JSON_ERROR, "Delete Data Gagal");
        }
    }
}

/* End of file olahraga.php */
/* Location: ./application/controllers/olahraga.php */