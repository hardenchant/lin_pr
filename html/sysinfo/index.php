<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Sysinfo</title>
		<style>
			.wrn {
				background-color: red;	
			}
			.normal {
				
			}
		</style>
	<head>
	<body>
		<table border>
			<tr>
				<td>
					<b>Time: </b>
					<?php
						echo exec('date -Iseconds | cut -c 12-19');
					?>
				</td>
				<td>
					<b>Версия nginx: </b>
					<?php 
						echo $_SERVER['HTTP_X_NGINX_V'];
					?>
				</td>
			</tr>
			<tr>
				<td>
					<b>Адрес с которого идёт nginx: </b>
					<?php 
						echo $_SERVER['REMOTE_ADDR'];
					?>
				</td>
				<td>
					<b>Адрес клиента: </b>
					<?php 
						echo $_SERVER['HTTP_X_R_IP'];
					?>
				</td>
			</tr>
			<tr>
				<td>
					<b>Порт с которого идёт nginx: </b>
					<?php 
						echo $_SERVER['REMOTE_PORT'];
					?>
				</td>
				<td>
					<b>Порт клиента: </b>
					<?php 
						echo $_SERVER['HTTP_X_R_PORT'];
					?>
				</td>
			</tr>
			<tr>
				<td>
					<b>Количество процессоров: </b>
					<?php
					$cores = exec("cat /proc/cpuinfo | grep 'cpu cores' | awk '{print \$4}'");
					echo $cores;
					$str = exec("cat /proc/loadavg | awk ' {print $1,$2,$3} '");
					$arr = sscanf($str, "%f %f %f");
					$warning1 = "normal";
					$warning2 = "normal";
					$warning3 = "normal";
					if($arr[0]/$cores > 0.8) $warning1 = "wrn";
					if($arr[1]/$cores > 0.8) $warning2 = "wrn";
					if($arr[2]/$cores > 0.8) $warning3 = "wrn";
					echo "</td><td><b>Load Average: </b>";
					echo "<span class=\"$warning1\">$arr[0]</span>
						<span class=\"$warning2\">$arr[1]</span>
						<span class=\"$warning3\">$arr[2]</span>";
					?>	
				</td>
			</tr>
			<tr>
				<td>
					<b>Update time: </b>
					<?php
						echo date("F d Y H:i:s.", filemtime("/home/lgv1/info/io.out"));
						
						$f = fopen("/home/lgv1/info/io.out", "r");
						if($f){
						
							echo "<br><b>iostat:</b><br>";
							
							$t = fgets($f,100);
							$res = sscanf($t, "%f %f %f %f %f %f");
							$warning = "normal";
							if($res[5]<20){
								$warning = "wrn";
							}
								printf("user: %.2f   nice: %.2f   system: %.2f   iowait: %.2f   steal: %.2f   <span class=\"$warning\">idle: %.2f</span>",
									$res[0], $res[1], $res[2], $res[3], $res[4], $res[5]);
							
							echo "<table border><tr>
									<td>Devices:</td>
									<td>tps</td>
									<td>kB_read/s</td>
									<td>kB_wrtn/s</td>
									<td>kB_read</td>
									<td>kB_wrtn</td>
									</tr>";
							while(!feof($f)){
								$t = fgets($f,100);
								
								echo "<br>";
								$t = fgets($f,100);
								$res = sscanf($t, "%s %f %f %f %f %f");
								printf("<tr>
											<td>%s</td>
											<td>%.2f</td>
											<td>%.2f</td>
											<td>%.2f</td>
											<td>%.2f</td>
											<td>%.2f</td>
									</tr>", $res[0], $res[1], $res[2], $res[3], $res[4], $res[5]);
								$t = fgets($f,100);
								
								echo "<br>";
							}
							echo "</table>";
							fclose($f);
						}
						else
						{
							echo "Read file error";
						}
					?>
				</td>
				<td>
					<b>File system:</b>
					<?php
						$f = fopen("/home/lgv1/info/fsstat.out", "r");
						if($f){
							while(!feof($f)){
							$t = fgets($f,100);
							echo $t;
							echo "<br>";
							}
							fclose($f);
						}else
						{
						echo "Read file error";
						}
					?>
				</td>
			</tr>
			
			
			<tr>
				<td>
					<b>Update time: </b>
					<?php
					echo date("F d Y H:i:s.", filemtime("/home/lgv1/info/netstat.out"));
					?>
					<br><b>Загрузка сетевых интерфейсов:</b><br>
					<?php
						
						$f = fopen("/home/lgv1/info/netstat.out", "r");
						if($f){
							$lines = exec("cat /proc/net/dev | wc -l");
							echo "<table border><tr>
										<td>R-bytes</td>
										<td>R-packets</td>
										<td>T-bytes</td>
										<td>T-packets</td>
								</tr>";
							
							for($i=0;$i<($lines/2);$i++){
							$t = fgets($f,100);
							fgets($f,100);
							$arr = sscanf($t,"%s %d %d %d %d %d %d %d %d %d %d %d %d %d");
							echo "<tr>
									<td>$arr[0]</td>
									<td>$arr[1]</td>
									<td>$arr[8]</td>
									<td>$arr[9]</td>
								</tr>";
							}
							echo "</table>";
							fclose($f);
						}else
						{
						echo "Read file error";
						}
					?>
				</td>
				<td>
					<b>Оперативная память(Mb):</b><br>
					<?php
						$mem = exec("free -m | sed '1d' | head -n1");
						$arr = sscanf($mem, "%s %d %d %d %d %d %d");
						$warning = "normal";
						if($arr[6]/$arr[1] < 0.2)
						{
							$warning = "wrn";
						}
						echo "<table border><tr>
										<td></td>
										<td>total</td>
										<td>used</td>
										<td>free</td>
										<td>shar</td>
										<td>cache</td>
										<td>avail</td>
									</tr>
						  			<tr>
										<td>Memory</td>
										<td>$arr[1]</td>
										<td>$arr[2]</td>
										<td>$arr[3]</td>
										<td>$arr[4]</td>
										<td>$arr[5]</td>
										<td><span class=\"$warning\">$arr[6]</span></td>
									</tr>
							</table>";
					?>
				</td>
				
			</tr>
			<tr>
				<td>
				<b>Update time: </b>
				<?php
				echo date("F d Y H:i:s.", filemtime("/home/lgv1/info/tcpdumpold.out"));
				?>
				<br>
				<?php
					$tcp = exec("wc -l /home/lgv1/info/tcpdumpold.out | awk '{print $1}'");
					$udp = exec("wc -l /home/lgv1/info/udpdumpold.out | awk '{print $1}'");
					$all = exec("wc -l /home/lgv1/info/dumpold.out | awk '{print $1}'");
					echo "Пакетов tcp: ";
					echo $tcp;
					echo "<br>Пакетов udp: ";
					echo $udp;
					echo "<br>Пакетов всего: ";
					echo $all;
					echo "<br><b>Процент tcp трафика от общего: </b>";
					echo $tcp/$all*100;
					echo "%<br><b>Процент udp трафика от общего: </b>";
					echo $udp/$all*100;
					echo "%";
				?>
				</td>
				<td>
				<b>Update time:</b>
				<?php
					echo date("F d Y H:i:s.", filemtime("/home/lgv1/info/netstat.out"));
					echo "<br>Всего сессий: ";
					echo exec("wc -l /home/lgv1/info/allsessions.out | awk '{print $1}'");
					echo "<br>TCP сессий: ";
					echo exec("wc -l /home/lgv1/info/tcpsessions.out | awk '{print $1}'");
					echo "<br>UDP сессий: ";
					echo exec("wc -l /home/lgv1/info/udpsessions.out | awk '{print $1}'");
					
				?>
				</td>
			</tr>
		</table>
	</body>
</html>
