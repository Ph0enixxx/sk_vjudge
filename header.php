<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-12-07 14:31:17
        Filename: header.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
			<script language="javascript" src="javascript/jquery-2.1.3.min.js"></script>
			<script language="javascript" src="javascript/common.js"></script>
			<link rel="stylesheet" href="css/common.css" type="text/css" />
			<div class='header'>
				<div class='navigator'>
						<h2 style='display: inline-block;'>Virtual Judge</h2><font style='display: inline-block; width: 220px;'>&nbsp;</font>
                    	<a href="index.php">首页</a><font style='display: inline-block; width: 40px;'>&nbsp;</font>
                    	<a href="rank.php">排名</a><font style='display: inline-block; width: 40px;'>&nbsp;</font>
                    	<a href="status.php">状态</a><font style='display: inline-block; width: 40px;'>&nbsp;</font>
                    	<?php
                        	require_once("api.php");
							if ($app->user->isLogin())
								echo '<font style="display: inline-block; padding: 5px; border-radius: 5px;">'.$app->user->getUser().'</font> => <a href="admin/status.php?action=logout&url=../index.php">登出</a>';
							else
								echo '<a href="admin/status.php?action=login&url=../index.php">登陆</a>';
						?>
            	</div>
				<?php
					if (!strstr($_SERVER['REQUEST_URI'], 'index.php'))
					{
						require_once('linux.php');
						$sys = sys_linux();
						?>
							<style>
								.bar {
									width: 500px;
									height: 3px;
									background-color: #00A;
									border: 1px #0A0 solid;
									border-radius: 1px;
								}
								.bar div {
									width: <?php echo $sys['memUsed']*500/$sys['memTotal'].'px;'; ?>
									height: 3px;
									background-color: #A00;
								}
							</style><br /><br /><br />
							<table style='font-size: 11px;'>
								</tr>
									<td>
										服务器核心
									</td>
									<td>
										<?php echo $sys['cpu']['num'].'核心 => '.$sys['cpu']['model']; ?>
									</td>
								<tr>
								<tr>
									<td>
										服务器内存
									</td>
									<td>
										<div class='bar'>
											<div>&nbsp;</div>
										</div>
									</td>
								<tr>
								</tr>
									<td>
										服务器运行时间
									</td>
									<td>
										<?php echo $sys['uptime']; ?>
									</td>
								</tr>
								</tr>
									<td>
										服务器均衡负载
									</td>
									<td>
										<?php echo str_replace(' ', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $sys['loadAvg']); ?>
									</td>
								</tr>
							</table>	
						<?php
					} else {
						?><br /><br /><br />
							<a href='http://www.90its.cn/' target='_blank' class='btn'>访问Cello Studio团队</a><br /><br /><br />
							<a href='http://github.com/springhack' target='_blank' class='btn'>帮助我改善这个项目</a>
						<?php
					}
				?>
			</div>
