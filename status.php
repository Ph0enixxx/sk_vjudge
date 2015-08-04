<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-08-04 13:36:34
        Filename: status.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Status List</title>
        <style>
			
		</style>
    </head>
    <body>
    	<table border="1">
        	<tr>
            	<td>
                	Run ID
                </td>
                <td>
                	User
                </td>
                <td>
                	Problem
                </td>
                <td>
                	Result
                </td>
                <td>
                	Memory
                </td>
                <td>
                	Time
                </td>
                <td>
                	Language
                </td>
                <td>
                	Submit Time
                </td>
            </tr>
            <?php
            	require_once('api.php');
				require_once('classes/Record.php');
				$db = new MySQL();
				$arr = $db->from('Record')->limit(100, 0)->order('DESC', 'time')->select('id')->fetch_all();
				for ($i=0;$i<count($arr);++$i)
				{
					$pro = new Record($arr[$i]['id']);
					$res = $pro->getInfo();
					?>
            <tr>
                <td>
                	<?php echo $res['id']; ?>
                </td>
                <td>
                	<?php echo $res['user']; ?>
                </td>
                <td>
                	<?php echo $res['oj'].' - '.$res['tid']; ?>
                </td>
                <td>
                	<?php echo $res['result']; ?>
                </td>
                <td>
                	<?php echo $res['memory']; ?>
                </td>
                <td>
                	<?php echo $res['long']; ?>
                </td>
                <td>
                	<?php echo $res['lang']; ?>
                </td>
                <td>
                	<?php echo date("Y-M-D H:i:s", $res['time']); ?>
                </td>
            </tr>
                    <?php
				}
			?>
        </table>
    </body>
</html>