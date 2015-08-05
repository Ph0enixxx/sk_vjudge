<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-08-04 17:31:10
        Filename: manager.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Rank List</title>
    </head>
    <body>
    	<?php
        	function secToTime($times){
				$result = '00:00:00';
				if ($times>0) {
					$hour = floor($times/3600);
					$minute = floor(($times-3600 * $hour)/60);
					$second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
					$result = $hour.':'.$minute.':'.$second;
				}
				return $result;
			}
		?>
    	<?php
        	require_once("api.php");
			$db = new MySQL();
			$start = $app->setting->get("startTime", time() + 10);
			if ($start>time())
				die('<center><h1>Rank not start !</h1></center></body></html>');
			$time = $app->setting->get("lastCache", 0);
			if ((time() - intval($time)) > 30)
			{
				$u_list = $app->user->getUserList();
				$p_list = $db->from("Problem")->select()->fetch_all();
				$list = array();
				for ($i=0;$i<count($u_list);++$i)
				{
					$list[$i] = array(
							'user' => $u_list[$i],
							'time' => 0,
							'deal' => 0
						);
					for ($j=0;$j<count($p_list);++$j)
					{
						$yes = $db->from("Record")
									->where("`oid`='".$p_list[$j]['id']."' AND `user`='".$u_list[$i]."' AND `result`='Accepted'")
									->order("ASC", "time")
									->select()
									->fetch_one();
						if ($yes == "")
							$no = $db->from("Record")
									->where("`oid`='".$p_list[$j]['id']."' AND `user`='".$u_list[$i]."' AND `result`<>'Accepted'")
									->order("ASC", "time")
									->select()
									->num_rows();
						else
							$no = $db->from("Record")
									->where("`oid`='".$p_list[$j]['id']."' AND `user`='".$u_list[$i]."' AND `result`<>'Accepted' AND `time`<".$yes['time'])
									->order("ASC", "time")
									->select()
									->num_rows();
						$list[$i][$j] = array(
								'pid' => $p_list[$j]['id'],
								'result' => ($yes == "")?"no":"yes",
								'time' => ($yes == "")?"0":(intval($yes['time']) - $start),
								'wrong' => $no
							);
						if ($yes != "")
						{
							$list[$i]['time'] += ($list[$i][$j]['time'] + $list[$i][$j]['wrong']*1200);
							$list[$i]['deal']++;
						}
					}
				}
				for ($i=0;$i<count($list)-1;++$i)
					for ($j=$i+1;$j<count($list);++$j)
						if ($list[$i]['deal'] < $list[$j]['deal'])
						{
							$tmp = $list[$i];
							$list[$i] = $list[$j];
							$list[$j] = $tmp;
						} else {
							if ($list[$i]['time'] != 0 || $list[$j]['time'] != 0)
								if (($list[$i]['time'] > $list[$j]['time']) || ($list[$i]['time'] == 0))
								{
									$tmp = $list[$i];
									$list[$i] = $list[$j];
									$list[$j] = $tmp;
								}
						}
				$app->setting->set("lastArray", serialize($list));
				$app->setting->set("lastCache", time());
			} else {
				$list = unserialize($app->setting->get("lastArray", "a:0:{}"));
			}
		?>
        <center>
        	<?php require_once("header.php"); ?>
        	<h1>Rank List</h1>
    		<table>
            	<tr style="color: #FFF; background-color: #0995C4;">
                	<td>
                    	User Name
                    </td>
                    <?php
                    	for ($i=1;$i<=$db->from("Problem")->select()->num_rows();++$i)
							echo '<td align="center" width="40">'.$i.'</td>';
					?>
                </tr>
            	<?php
                	for ($i=0;$i<count($list);++$i)
					{
						echo '<tr'.(($i%2)?' style="background-color: #CEFDFF;"':'').'><td style=" border-bottom: 1px dotted #CCCCCC;" width="200">'.$list[$i]['user'].'</td>';
						foreach ($list[$i] as $key => $val)
							if (!is_string($key))
							{
								if ($list[$i][$key]['result'] == 'yes')
									echo '<td align="center" style="background-color: #0F0; border-bottom: 1px dotted #CCCCCC;">'.secToTime($list[$i][$key]['time']).'<br />';
								else
									if ($list[$i][$key]['wrong'] != 0)
										echo '<td align="center" style="background-color: #F00; border-bottom: 1px dotted #CCCCCC;">';
									else
										echo '<td style=" border-bottom: 1px dotted #CCCCCC;" align="center">';
								if ($list[$i][$key]['wrong'] != 0)
									echo '-'.$list[$i][$key]['wrong'];
								echo '</td>';
							}
					}
				?>
        	</table>
            <br />
            <br />
        </center>
    </body>
</html>