<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CronRunner
{
 	private $CI;

	public function __construct()
	{
	    $this->CI =& get_instance();
	}

	private function calculateNextRun($obj)
	{
		return (time() + $obj->interval_sec);
	}

 	public function run()
 	{
	    // $query = $this->CI->db->where('is_active', 1)->where('now() >= next_run_at OR next_run_at IS NULL', '', false)->from('cron')->get();
	    // if ($query->num_rows() > 0) {
	    //  	$env = getenv('CI_ENV');
	    //    	foreach ($query->result() as $row) {
		//         $cmd = "export CI_ENV={$env} && {$row->command}";
		//         $this->CI->db->set('next_run_at', 'FROM_UNIXTIME('.$this->calculateNextRun($row).')', false)->where('id', $row->id)->update('cron');
		//         // here ##############
        //         $output = shell_exec($cmd);
        //         // end
		//         $this->CI->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('cron');
	    //    	}
	    // } 
        while (true) {
            sleep(1); // WAIT FOR X SECONDS
            $this->CI->db->set( ['status'=>2] )->where('TIME_TO_SEC(TIMEDIFF(now(), created_at))>=60 AND status=0')->update('call_room');
			$this->CI->db->set( ['status'=>4] )->where('(TIME_TO_SEC(TIMEDIFF(now(), update_at))>=60 OR ISNULL(update_at)) AND status=1')->update('call_room');
			//print($this->CI->db->last_query());
        }
  	}
}