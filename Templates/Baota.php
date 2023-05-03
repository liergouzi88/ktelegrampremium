<p>
	<strong><span style="font-size:24px;">宝塔下执行计划任务</span></strong> 
</p>

	<strong><img src="/install/20200507185310433.png"  width="400" height="282"  alt="" /></strong>
</p>
<p>
	<span style="font-size:14px;">任务名称：钱包监控</span>
</p>
<p>
	<span style="font-size:14px;">时间：1分钟</span>
</p>
<p>
	<span style="font-size:14px;">shell 脚本：</span></br><pre>#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH
step=20
for (( i = 0; i < 60; i=(i+step) )); do
curl -sS --connect-timeout 10 -m 60 'https://<?php echo $_SERVER['SERVER_NAME'];?>/index.php/Api/pay'
echo "----------------------------------------------------------------------------"
endDate=`date +"%Y-%m-%d %H:%M:%S"`
echo "★[$endDate] Successful"
echo "----------------------------------------------------------------------------"
sleep $step
done
exit 0
</pre>
<p>
<p>
	<br /><img src="/install/微信截图_20230503041209.png"  width="400" height="282"  alt="" />
</p>
<p>
	<span style="font-size:14px;">如果不会 设置 可以找 @zf789 协助</span>
</p>