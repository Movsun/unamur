<?php
namespace App;
class Test{
	public $value = 0;

	function __construct() {
       // while (true){
       // 	 $this->value ++;
       // 	 sleep(5);
       // }
    }

    public function get(){
    	$this->value++;
    	return $this->value;
    }

}

?>