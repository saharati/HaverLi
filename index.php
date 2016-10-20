<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="home">
<div id="contentInner">
<ul class="bxslider">
<li><img title="גם הירו מחכה לכם בפינת אימוץ" src="/images/home/temp1.jpg"></li>
<li><img src="/images/home/temp2.jpg"></li>
<li><img title="גם דאצ'ס מחכה לכם בפינת אימוץ" src="/images/home/temp3.jpg"></li>
<li><img src="/images/home/temp4.jpg"></li>
<li><img title="גם טייגר מחכה לכם בפינת אימוץ" src="/images/home/temp5.jpg"></li>
<li><img src="/images/home/temp6.jpg"></li>
</ul>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<script>
$(document).ready(function(){
  $('.bxslider').bxSlider({
    slideWidth: 290,
    minSlides: 2,
    maxSlides: 3,
    moveSlides: 3,
    slideMargin: 15,
    captions: true,
    pager: false,
    nextText: 'הבא',
    prevText: 'הקודם',
    auto: true,
    pause: 6000,
    autoHover: true,
    shrinkItems: true
  });
});
</script>
</body>
</html>