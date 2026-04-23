<?php
$dir = dirname($_SERVER['REQUEST_URI']);
$is_admin = (strpos($dir, '/adm') !== false);
$img_path = $is_admin ? '../images/лого2.png' : 'images/лого2.png';
?>
<img src="<?php echo $img_path; ?>" alt="ТехноЛаб" class="logo-img">
<div class="logo-name">ТехноЛаб</div>