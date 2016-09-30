<?php
#-----------------------------------------------------#
# BOT SIMSIMI REPLY COMMENT
# Author : Star Thiện (_Alone_Boy_)
# Date: 25/5/2016
# FB.COM/THIEN.IT.BKDN
# Vui Lòng Kính Trọng Người Làm Ra Nó. Không Nên Xóa Dòng Này
#-----------------------------------------------------#
set_time_limit(0);
require ('funcEmo.php');
$iduser = "########"; // ID Người Sử Dụng Simsimi Cmt
$token = "########"; // Token Của Nick simsimi
$getID = json_decode(auto('https://graph.facebook.com/me?access_token='.$token.'&fields=id'),true);
$getStt = json_decode(auto('https://graph.facebook.com/'.$iduser.'/feed?limit=1&access_token='.$token),true);
$log = json_encode(file('log.txt'));
for($i=1;$i<=count($getStt[data]);$i++){
	$getcmt = json_decode(auto('https://graph.facebook.com/'.$getStt[data][$i-1][id].'/comments?access_token='.$token.'&limit=1000&fields=id,from,message'),true);
	if(count($getcmt[data]) > 0){
		for($c=1;$c<=count($getcmt[data]);$c++){
			$log_f = explode($getcmt[data][$c-1][id],$log);
			if(count($log_f) > 1){
				echo'Done! ';
			}else{
				$log_x = $getcmt[data][$c-1][id].'_';
				$log_y = fopen('log.txt','a');
				fwrite($log_y,$log_x);
				fclose($log_y);
				$cmt = trim($getcmt[data][$c-1][message]);
				$a = 'add'; // từ khóa để sim gửi kết bạn
				if(strpos($cmt, $a)===false){
					$str = $getcmt[data][$c-1][from][name];
					$traloi = '#'.str_replace( ' ', '_', $str).': '; // tag
					$traloi .= StarSim($getcmt[data][$c-1][message]);
					if($getcmt[data][$c-1][from][id] !== $getID[id]) {
						auto('https://graph.facebook.com/'.$getStt[data][$i-1][id].'/comments?access_token='.$token.'&message='.urlencode(Emo($traloi)).'&method=post');
						}
					}else{
						auto('https://graph.facebook.com/me/friends?uid='.$getcmt[data][$c-1][from][id].'&access_token='.$token.'&method=post');
						$str = $getcmt[data][$c-1][from][name];
						$traloi = '#'.str_replace( ' ', '_', $str).': ';
						$traloi .= 'Gửi Lời Mời Kết Bạn Rồi Nha :D';
						auto('https://graph.facebook.com/'.$getStt[data][$i-1][id].'/comments?access_token='.$token.'&message='.urlencode(Emo($traloi)).'&method=post');
				} 
			}

		}
	}
}
// Hàm Phản Hồi SIMSIMI
function StarSim($noidung){
$c = curl_init("http://staronlike.com/api.php?key=6062cdfc-280a-4262-ba0d&text=$noidung"); 
// Key vô hạn nên các bạn dùng thoải mái nha.. khi nào web mình die thì thôi...api này các bạn có thể dùng app simsimi trên điện thoại để dạy nhá. :D
curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
$phanhoi = curl_exec($c);
curl_close($c);
if(strpos($phanhoi, '<head><title>400')===false)
$st = 1;
else $phanhoi = 'Hệ Thống Phản Hồi Đang Quá Tải';
return $phanhoi;
}

function auto($url){
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $url);
$ch = curl_exec($curl);
curl_close($curl);
return $ch;
}
?>