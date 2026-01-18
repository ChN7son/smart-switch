<?php
$proc=$_REQUEST['proc'];
$pre='SW';//就是兩個英文字喔！
$IPPRE='192.168.47.';
if ($proc!='init' && $proc!='sinit') return;
require('config.php');
require('dbconfig.php');
$RG='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@$^&*()~][{},.;<>_-+=/';
$URG='abcdefghjkmnpqrstuvwxyz123456789';//管理密碼
$DRG='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
$db_conn=db_connect("host=$HOST dbname=$DBNAME user=$WRITER password=$WRITER_PW");
db_set_encoding($db_conn,'utf-8');
if (!$db_conn)
     die("DataBase $DBNAME open fail!");

 $QUERY="select id from host where id like '${pre}%' order by id desc limit 1";
 $result=db_exec($db_conn,$QUERY);
 if (!$result)
	die("DataBase $QUERY exec fail!");
 $numrow=db_NumRows($result);
 if ($numrow==0)
	$id_num=0;
 else
 {
	$ARAW=db_fetch_row($result,0);
	$id_num=(int)substr($ARAW[0],2);
	$id_num++;
 }
 $nid=$pre.sprintf('%06d',$id_num);
 $CLIENTIP=$IPPRE.($id_num+10);
 if ($id_num>230)
	die('No IP!');
 $devpwd='';
 for ($i=0;$i<40;$i++)
 {
	$devpwd=$devpwd.substr($RG,rand(0,strlen($RG)-1),1);
 }
 $manpwd='';
 for ($i=0;$i<10;$i++)
 {
    $manpwd=$manpwd.substr($URG,rand(0,strlen($URG)-1),1);
 }
 $manpwd='123456';
 $vpnpwd='';
 for ($i=0;$i<10;$i++)
 {
    $vpnpwd=$vpnpwd.substr($URG,rand(0,strlen($URG)-1),1);
 }
 $ide='';
 for ($i=0;$i<40;$i++)
 {
	$ide=$ide.substr($DRG,rand(0,strlen($DRG)-1),1);
 }
 $IP=$_SERVER['REMOTE_ADDR'] ;
 $DAYTIME=date('Y.m.d H:i:s');
/*****
id       | character(8)                | 非 Null
 ide      | character(40)               | 
 manpwd   | character(20)               | 非 Null
 vpnpwd   | character(20)               | 
 clientip | character(15)               | 
 cday     | timestamp without time zone | 非 Null 預設值 now()
 atime    | integer                     | 
 dname    | character(20)               | 
 lineid   | character varying(50)       | 
 linekey  | character varying(50)       | 
 remark   | text                        | 
  欄位   |     型別      | 修飾詞  
---------+---------------+---------
 id      | integer       | 非 Null
 host    | character(8)  | 
 devname | character(20) | 
 type    | integer       | 
 state   | integer       | 
 access  | integer       | 
 price   | integer       | 
 ip      | integer       | 
 w       | integer       | 
 top     | integer       | 
*******/
 $QUERY="insert into host(id,ide,manpwd,vpnpwd,clientip,cday,atime,dname,lineid,linekey,remark) values ('$nid','$devpwd','$manpwd','$vpnpwd','${CLIENTIP}','${DAYTIME}',0,'TEST mechine','','','');";
 $result=db_exec($db_conn,$QUERY);
 if (!$result)
	die("DataBase $QUERY exec fail!");
 $nip=0;
 for ($i=0;$i<3;$i++)
  for ($j=1;$j<=5;$j++)
 {
	$devname=substr($RG,$i,1).' 0'.$j;
	$nip++;
 	$QUERY="insert into device(id,host,devname,type,state,access,price,ip,w,top) values ((select max(id)+1 from device),'$nid','$devname',null,
	0,0,10,$nip,null,0);";
 	$result=db_exec($db_conn,$QUERY);
 	if (!$result)
		die("DataBase $QUERY exec fail!");
 }
 $fp=fopen("$VPNFN",'a');
 fwrite($fp,"\"${nid}\" l2tpd \"${vpnpwd}\" ${CLIENTIP}\n");
 fclose($fp);

 $SERVERIP=$_SERVER['SERVER_ADDR'];
 echo "success\n";
 echo "#gpio\n${GPIO}\n";
 echo "#on delay\n${ON_DELAY}\n";
 echo "#off delay\n${OFF_DELAY}\n";
 echo "#name\n${nid}\n";
 echo "#passwd\n${devpwd}\n";
 echo "#vpnpwd\n${vpnpwd}\n";
 echo "#serverip\n${SERVERIP}\n";
 echo "#clientip\n${CLIENTIP}\n";
 echo "#psk\n${VPNPSK}\n";
 if ($proc=='init')
 	echo "#url\n${URL}\n#jurl\n${JURL}";
 else if ($proc=='sinit')
        echo "#url\n${URLS}\n#jurl\n${JURLS}";
?>
