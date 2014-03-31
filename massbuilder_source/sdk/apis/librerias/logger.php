<?php

class MassBuilderLogSimple {

    var $log;
    var $msj;
    var $LOG_ID;
    var $severity;

    const LLOG_INFO = 1;
    const LLOG_WARNING = 2;
    const LLOG_CRITICAL = 3;
    const LLOG_DEBUG = 4;

        function MassBuilderLogSimple( $msj , $LOG_ID , $severity = 1 ){
                $this->msj = $msj;
                $this->LOG_ID = $LOG_ID;
				
				
						// write out the log line ::
						$this->log .= $line."\n";
						
						
						$this->severity = $severity;


                        // Make a human-friendly verion of the severity flag.
                        switch($severity)
                        {
									default:
									case self::LLOG_INFO: {
                                        $severity_str = "INFO";
                                        break;
                                }
									case self::LLOG_WARNING: {
                                        $severity_str = "WARNING";
                                        break;
                                }
									case self::LLOG_CRITICAL: {
                                        $severity_str = "CRITICAL";
                                        break;
                                }
									case self::LLOG_DEBUG: {
                                        $severity_str = "DEBUG";
                                        break;
                                }
                        }


                                $this->log .="Severity: {$severity_str}\n";
                                $get = "";
                                foreach ($_GET as $k => $v){
                                        $get .= "{$k}={$v},";
                                }
                                $post = "";
                                $get = rtrim($get,",");
                                foreach ($_POST as $k => $v){
                                $post .= "{$k}={$v},";
                                }
                                $session = "";
                                $post = rtrim($post,",");
                                if (isset($_SESSION))
                                foreach ($_SESSION as $k => $v){
                                        $session .= "{$k}={$v},";
                                }
                                $session = rtrim($session,",");
                                $args_ = "";
                                if(isset($_SERVER["argc"]) && $_SERVER["argc"] >= 1)
                                foreach ($_SERVER["argv"] as $k => $v){
                                        $args_ .= "{$k}={$v},";
                                }
                                $args_ = rtrim($args_,",");

                                $stdout0 = "\n\n" . date('Y-M-d H:i:s - ') . "pid:[" . $LOG_ID . "]\n";
                                $stdout0.= "SERVER_NAME\t:[{" . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '') . "}] \n";
                                $stdout0.= "HTTP_REFERER\t:[{" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') . "}] \n";
                                $stdout0.= "GET\t\t:[{$get}] \n";
                                $stdout0.= "POST\t\t:[{$post}] \n";
                                $stdout0.= "args\t\t:[$args_] \n";
                                $stdout0.= "SESSION\t\t:[{$session}] \n";
                                $stdout0.= "DB_SERVER\t:[".HOST."] DB_NAME:[".DB."] " . "\n";

                                $this->log .= $stdout0;

                               $this->log .="Back-trace: \n{$content}\n\n";
							   							   
        }

		function toString(){
			return $this->log;
		}
		function printt( $msj ){			
			 // Make a human-friendly verion of the severity flag.
                        switch($this->severity)
                        {
									default:
									case self::LLOG_WARNING: 
									case self::LLOG_CRITICAL: 
									case self::LLOG_INFO: {
                                        ECHO $msj. "\n"; //$this->log;
                                        break;
                                }
									case self::LLOG_DEBUG: {
                                        ECHO $msj . "\n". $this->log;
                                        break;
                                }
                        }
						
		}
		
        //Destructor
        function __destruct() {
                $this->destroy();
        }
        function destroy(){ }



}

?>




				