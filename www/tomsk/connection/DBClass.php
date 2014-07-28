<?php
//связь с БД
define ( 'HOSTNAME', 'localhost');
define ( 'DATABASE', 'stelsib');
define ( 'USERNAME', 'root');
define ( 'PASSWORD', '');
define ( 'TYPE',     'mysqli'); 
define ( 'ENCODING',  'utf8');
$mysqli = M_Core_DB::getInstance(array('host'=>HOSTNAME,'login'=>USERNAME,'password'=>PASSWORD,'db'=>DATABASE, 'type'=>TYPE, 'encoding'=>ENCODING));
class M_Core_DB{

    /**
     * Module Instance for Singleton pattern
     *
     * @var M_Core_DB
     */
    static private $instance = null;

    /**
     * Singleton main method
     *
     * @param array $params
     * @return M_Core_DB
     */
    static function getInstance($params = false){
        if (self::$instance == null){
            self::$instance = new M_Core_DB($params);
        }
        return self::$instance;
    }

    /**
     * Mysqli connection handler
     *
     * @var mysqli
     */
    public $handler = null;

    /**
     * Mysqli statement handler
     *
     * @var mysqli_stmt
     */
    protected $stmt = null;

    /**
     * Mysql result handler
     * 
     * @var resource
     */
    protected $result = null;

    /**
     * Stmt result keys
     *
     * @var arrays
     */
    protected $keys = array();
    /**
     * Stmt metadata
     *
     * @var mysqli_metadata
     */

    /**
     * Database connect type
     * 
     * @var string
     */
    protected $meta = null;

    /**
     * Module constructor
     *
     * @param array $params
     */
    public function __construct($params = false){
        if ($params && $params['type'] == 'mysqli')
            $this->connect_mysqli($params);
        if ($params && $params['type'] == 'mysql')
            $this->connect_mysql($params);
        $this->type = $params['type'];
        if (array_key_exists('encoding',$params)) $this->query("set names ".$params['encoding']); else $this->query("set names UTF8");
    }

    /**
     * Connect to database - mysqli method
     *
     * @param array $params
     */
    public function connect_mysqli($params){
        if (!$params) throw new Exception('Params error');
        if (!$this->handler = new mysqli($params['host'],$params['login'],$params['password'],$params['db']))
		{
			throw new Exception(mysqli_error($this->handler));
		}
		
    }

    /**
     * Connect to database - mysql method
     *
     * @param array $params
     */
    public function connect_mysql($params){
        if (!$params) throw new Exception('Params error');
        if (!$this->handler = mysql_connect($params['host'],$params['login'],$params['password'])) throw new Exception('Error connectind to database server');
        if ($this->handler) {
            if (!mysql_select_db($params['db'], $this->handler)) throw new Exception('Error selecting database');
        }
    }

    /**
     * Send query
     *
     * @param string $query
     * @param mixed(string,array) $params
     */
    public function query($query, $params = false){
		
        if ($this->type == 'mysql')
            return $this->query_mysql($query, $params);
        if ($this->type == 'mysqli')
            return $this->query_mysqli($query, $params);
    }

    /**
     * Send query - mysqli
     *
     * @param string $query
     * @param mixed(string,array) $params
     */
    public function query_mysqli($query, $params = false){
        $this->stmt = $this->handler->prepare($query);
        if (!$this->stmt){
            throw new Exception(mysqli_error($this->handler)."\n\n".$query); 
        }
        if ($params){ 
            if (!is_array($params))
			{
				$params = array($params);
			}
            array_unshift($params, str_repeat('s', count($params)));
            $refs = array();
            foreach ($params as $i => &$f) $refs[$i] = &$f;
            call_user_func_array(array(&$this->stmt, 'bind_param'),$refs);
        }
        $this->stmt->execute(); 
        $this->stmt->close();
        return true;
    }

    /**
     * Send query - mysqli
     *
     * @param string $query
     * @param mixed(string,array) $params
     */
    public function query_mysql($query, $params = false){
        if ($params !== false) {
            if (!is_array($params))
                $params = array($params);
            $arQuery = explode('?', $query);
            if (count($arQuery) - 1 != count($params))
                throw new Exception('Wrong count of arguments');
            $query = $arQuery[0];
            for ($i = 0; $i < count($params); $i++) {
                switch(gettype($params[$i])) {
                    case  'string': $query .= "'".mysql_real_escape_string($params[$i], $this->handler)."'".$arQuery[$i + 1];
                                    break;  
                    case 'integer': $query .= intval($params[$i]).$arQuery[$i + 1];
                                    break; 
                    case  'double': $query .= doubleval($params[$i]).$arQuery[$i + 1];
                                    break;
                    case 'boolean': $query .= (($params[$i])?1:0).$arQuery[$i + 1];
                                    break;
                    case    'NULL': $query .= intval(0).$arQuery[$i + 1];
                                    break;
                           default: throw new Exception('Wrong type of arguments');
                }
            }
        }
        $this->result = mysql_query($query, $this->handler);
        //echo $query."\n";
        return $this->result;
    }

    /**
     * Execute query
     *
     * @param string $query
     * @param mixed(string,array) $params
     */
    public function _execute($query,$params = false){
        $this->stmt = $this->handler->prepare($query);
        if (!$this->stmt){
            throw new Exception(mysqli_error($this->handler)."\n\n".$query); 
        }
        if ($params){
            if (!is_array($params)) $params = array($params);
            array_unshift($params, str_repeat('s', count($params)));
            $refs = array();
            foreach ($params as $i => &$f) $refs[$i] = &$f;
            call_user_func_array(array(&$this->stmt, 'bind_param'),$refs);
        }
        $this->stmt->execute();

        $this->meta = $this->stmt->result_metadata();
        $this->keys = array();
        foreach ($this->meta->fetch_fields() as $col) $this->keys[] = $col->name;
        $this->stmt->store_result(); 
    }

    /**
     * Fetch results
     *
     * @return array
     */
    public function fetch(){
        $values = array_fill(0, count($this->keys), null);
        $refs = array();
        foreach ($values as $i => &$f) $refs[$i] = &$f;

        call_user_func_array(array(&$this->stmt, 'bind_result'),$refs);
        $retval = $this->stmt->fetch();
        if (!$retval){
            $this->stmt->reset();
            return $retval;
        }
        return array_combine($this->keys, $refs);
    }

    /**
     * Fetch all results
     *
     * @param string $query
     * @param mixed(string,array) $params
     * @return array
     */
    public function fetchAll($query,$params = false){
        if ($this->type == 'mysqli') {
            $this->_execute($query,$params);

            $data = array();
            while ($row = $this->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        if ($this->type == 'mysql') {
            $ret = array();
            if ($this->query($query, $params))
                while($row = mysql_fetch_array($this->result))
                    $ret[] = $row;
            else
                throw new Exception('Error fetching data');
            return $ret;
        }
    }

    /**
     * Fetch one column
     *
     * @param string $query
     * @param mixed(string,array) $params
     * @return string
     */
    public function fetchOne($query, $params = false){
        if ($this->type == 'mysqli') {
            $this->_execute($query,$params);

            $this->stmt->bind_result($result);
            $this->stmt->fetch();
            $this->stmt->reset();
            return $result; 
        }
        if ($this->type == 'mysql') {
            if ($this->query($query, $params))
                $ret = mysql_fetch_row($this->result);
            else
                throw new Exception('Error fetching data');
            return $ret[0]; 
        }
    }

    /**
     * Result num rows
     *
     * @return integer
     */
    public function num_rows(){
        if ($this->type == 'mysqli')
            return $this->stmt->num_rows;
        if ($this->type == 'mysql')
            return mysql_num_rows($this->handler);
    }

    /**
     * Last inserted id
     *
     * @return integer
     */
    public function lastInsertId(){
        if ($this->type == 'mysqli')
            return $this->handler->insert_id;
        if ($this->type == 'mysql')
            return mysql_insert_id($this->handler);
    }
	// 
    public function queryQ($query)
	{
		return $this->handler->query($query);
	}
	public function fetchAssoc($k){
        //
        return $k->fetch_assoc();
    }
	public function num_r($k){
        // num_rows
        return $k->num_rows;
    }
	public function data_seek($position)
	{
		$this->stmt->data_seek($position);
		
	}
    
}
?>