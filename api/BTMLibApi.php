<?php
/**
 * Description of BTMLibApi<br />
 * This library was developed for retrieving data from a search by given city names<br />
 * Public Methods:<br />
 * public function __construct($from, $to, $language ,$encoding = null)<br />
 * public function loadDestinations()<br />
 * public function data()
 * @author 158 ltd | developer: Alexander Mihaylov.
 */
class BTMLibApi {
    private $from;
    private $to;
    private $encoding;
    private $data;
    private $locale;
    const SYS_URL = 'http://localhost/MTT/mod_online/';
    
    /**
     * <p>Construction of an btm http query that will get all the needed data.</p>
     * @param String $from A city name. You can use both english and bulgarian names
     * @param String $to A city name. You can use both english and bulgarian names
     */
    public function __construct($from, $to, $language ,$encoding = null) {
        $this->encoding = $encoding;
        $this->data = null;
        $this->locale = $language;
        
        $this->from = $from;
        $this->to = $to;
        
        if(!empty($this->encoding)){
            $this->from = mb_convert_encoding($from, 'utf-8', $this->encoding);
            $this->to = mb_convert_encoding($to, 'utf-8', $this->encoding);
        }
        
        $this->from = urlencode($this->from);
        $this->to = urlencode($this->to);
    }
    /**
     * This method executes the http query and loads the respose in a data array.<br />
     * Converts the encoding if encoding is set.<br />
     * You can access the data array from the public method data() (ex: $dest->data())
     */
    public function loadDestinations(){
        
        $destination = file_get_contents(self::SYS_URL."search.php?from={$this->from}&to={$this->to}&lang={$this->locale}");
        
        $this->data = unserialize($destination);
        
        if(!empty($this->encoding)) $this->data = self::convert_encoding ($this->data, $this->encoding);
    }
    
    private static function convert_encoding($data, $encoding){
        if(is_array($data)){
            foreach($data as $key => $element){
                $data[$key] = self::convert_encoding($element,$encoding);
            }
            return $data;
        }else{
            return mb_convert_encoding ($data, $encoding, 'utf-8');
        }
    }
    /**
     * The data array
     * @return Array With all the data retrieved from the execution of the http query 
     */
    public function data(){
        return $this->data;
    }
}
?>
