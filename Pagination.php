<?php
// Simple pagination class

class Pagination 
{
	private $_db;
    private $_page;
    private $_result;
    private $_total;
    private $_rows = [];

     /**
     * __construct
     * @param string  $db_name DataBase name
     * @param string  $table Table name
     * @param integer $num Needed amount of content per page
     */
    public function __construct($db_name, $table, $num){
        $num = $num;
        $this->_page = (int)$_GET['page'];
        $this->_db = new mysqli('localhost', 'root', '', $db_name);
        $count = $this->_db->query("SELECT COUNT(*) FROM $table");
        $temp = $count->fetch_array();
        if($temp[0] > 0){
            $tempcount = $temp[0];
            // total amount of pages
            $this->_total = (($tempcount - 1) / $num) + 1;
            $this->_total = intval($this->_total);
            $this->_page = intval($this->_page);

            if(empty($this->_page) || $this->_page < 0) $this->_page = 1;
            if($this->_page > $this->_total) $this->_page = $this->_total;

            // calculate from which number to display content
            $start = $this->_page * $num - $num;
            $query_start_num = " LIMIT $start, $num";
        }

   		 $this->_result = $this->_db->query("SELECT * FROM $table ORDER BY id DESC $query_start_num");
    }

    // echo out content
    public function get_content(){
        if($this->_result->num_rows > 0){
            $row = $this->_result->fetch_array();
            do{
				echo '<div class="content">
					<p class="title">'.$row['title'].'</p>
					</div>';
            }while($row = $this->_result->fetch_array());
        }
    }
    
	// pages links
    public function page_links()
    {
			// check if arrow back required 
			if ($this->_page != 1) {
				$pervpage = '<a href= ./index.php?page=1><<</a> 
				<a href= ./index.php?page='. ($this->_page - 1) .'><</a> ';
			}
			 
			// check if arrow forward required
			if ($this->_page != $this->_total){
				$nextpage = ' <a href= ./index.php?page='. ($this->_page + 1) .'>></a> 
			    <a href= ./index.php?page=' .$this->_total. '>>></a>'; 
			}
			
			// find two nearest pages with both edges, if they exist
			if($this->_page - 2 > 0){
				$page2left = ' <a href= ./index.php?page='. ($this->_page - 2) .'>'. ($this->_page - 2) .'</a>  '; 
			}
			
			if($this->_page - 1 > 0){
				$page1left = '<a href= ./index.php?page='. ($this->_page - 1) .'>'. ($this->_page - 1) .'</a>  '; 
			}
			
			if($this->_page + 2 <= $this->_total){
				$page2right = '  <a href= ./index.php?page='. ($this->_page + 2) .'>'. ($this->_page + 2) .'</a>'; 
			}
			
			if($this->_page + 1 <= $this->_total){
				$page1right = '  <a href= ./index.php?page='. ($this->_page + 1) .'>'. ($this->_page + 1) .'</a>';
			}

			// echo out pages links
			echo "<ul>
				  <li>".$pervpage."</li><li>".$page2left."</li><li>".$page1left."</li>
				  <li><a class='pagination-active'><b>".$this->_page."</b></a></li>
				  <li>".$page1right."</li><li>".$page2right."</li><li>".$nextpage."</li>
				 </ul>";
    }
}

?>