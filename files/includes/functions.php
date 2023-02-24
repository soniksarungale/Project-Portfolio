<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function getUrl($pname)
{
  $pname = trim($pname);
  $pname = str_replace(' ', '-', $pname);
  $pname = strtolower($pname);
  return preg_replace('/[^A-Za-z0-9\-]/', '', $pname);
}
function mobile(){
  $data = array(
      '/iphone/i' => 'iPhone',
      '/ipod/i' => 'iPod',
      '/ipad/i' => 'iPad',
      '/android/i' => 'Android',
      '/blackberry/i' => 'BlackBerry',
      '/webos/i' => 'Mobile'
  );

  //Return true if Mobile User Agent is detected
  foreach($data as $mk => $mv){
      if(preg_match($mk, $_SERVER['HTTP_USER_AGENT'])){
          return $mv;
      }
  }
  //Otherwise return false..
  return false;
}
function computer(){
   $data= array(
    '/windows nt 6.2/i'     =>  'Windows 8',
    '/windows nt 6.1/i'     =>  'Windows 7',
    '/windows nt 6.0/i'     =>  'Windows Vista',
    '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
    '/windows nt 5.1/i'     =>  'Windows XP',
    '/windows xp/i'         =>  'Windows XP',
    '/windows nt 5.0/i'     =>  'Windows 2000',
    '/windows me/i'         =>  'Windows ME',
    '/win98/i'              =>  'Windows 98',
    '/win95/i'              =>  'Windows 95',
    '/win16/i'              =>  'Windows 3.11',
    '/macintosh|mac os x/i' =>  'Mac OS X',
    '/mac_powerpc/i'        =>  'Mac OS 9',
    '/linux/i'              =>  'Linux',
    '/ubuntu/i'             =>  'Ubuntu',
  );

  foreach($data as $ck => $cv){
    if(preg_match($ck, $_SERVER['HTTP_USER_AGENT'])){
      return $cv;
    }
  }
  return false;
}
function browser(){
  $data = array (
    '/msie/i'       =>  'Internet Explorer',
    '/firefox/i'    =>  'Firefox',
    '/Chrome/i'     =>  'Chrome',
    '/safari/i'     =>  'Safari',
    '/opera/i'      =>  'Opera',
    '/netscape/i'   =>  'Netscape',
    '/maxthon/i'    =>  'Maxthon',
    '/konqueror/i'  =>  'Konqueror',
    '/mobile/i'     =>  'Handheld Browser'

  );

  foreach($data as $bk => $bv){
    if(preg_match($bk, $_SERVER['HTTP_USER_AGENT'])){
      return $bv;
    }
  }
  return false;
}
function browserVer($browser)
{
  $u_agent = $_SERVER['HTTP_USER_AGENT'];
  $known = array('Version', $browser, 'other');
  $pattern = '#(?<browser>' . join('|', $known) .
  ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
      // we have no matching number just continue
  }

  $i = count($matches['browser']);
  if ($i != 1) {
      if (strripos($u_agent,"Version") < strripos($u_agent,$browser)){
          $version= $matches['version'][0];
      }
      else {
          $version= $matches['version'][1];
      }
  }
  else {
      $version= $matches['version'][0];
  }

  if ($version==null || $version=="") {$version=null;}
  return $version;
}
?>
