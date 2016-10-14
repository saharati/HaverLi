<?php require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<div id="content-wrap">
<div class="center-block">
<a class="button" title="הוספת אלבומים" href="/private/addAlbums.php"><i class="fa fa-picture-o" aria-hidden="true"></i> הוספת אלבומים</a>
<a class="button" title="עריכת אלבומים" href="/private/updateAlbums.php"><i class="fa fa-file-image-o" aria-hidden="true"></i> עריכת אלבומים</a>
<a class="button" title="הוספת אירועים" href="/private/addEvents.php"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> הוספת אירועים</a>
<a class="button" title="עריכת אירועים" href="/private/updateEvents.php"><i class="fa fa-calendar-minus-o" aria-hidden="true"></i> עריכת אירועים</a>
<a class="button" title="הוספת מוצרים" href="/private/addProducts.php"><i class="fa fa-cart-plus" aria-hidden="true"></i> הוספת מוצרים</a>
<a class="button" title="עריכת מוצרים" href="/private/updateProducts.php"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> עריכת מוצרים</a>
<a class="button" title="הוספת מאמרים" href="/private/addArticles.php"><i class="fa fa-file" aria-hidden="true"></i> הוספת מאמרים</a>
<a class="button" title="עריכת מאמרים" href="/private/updateArticles.php"><i class="fa fa-file-text" aria-hidden="true"></i> עריכת מאמרים</a>
<a class="button" title="הוספת סרטונים" href="/private/addVideos.php"><i class="fa fa-video-camera" aria-hidden="true"></i> הוספת סרטונים</a>
<a class="button" title="עריכת סרטונים" href="/private/updateVideos.php"><i class="fa fa-file-video-o" aria-hidden="true"></i> עריכת סרטונים</a>
<a class="button" title="הוספת משתמשים" href="/private/addUsers.php"><i class="fa fa-user-plus" aria-hidden="true"></i> הוספת משתמשים</a>
<a class="button" title="עריכת משתמשים" href="/private/updateUsers.php"><i class="fa fa-users" aria-hidden="true"></i> עריכת משתמשים</a>
<a class="button" title="חיפושים" href="/private/search.php"><i class="fa fa-search" aria-hidden="true"></i> חיפושים</a>
<a class="button" title="שינוי סיסמה" href="/private/change.php"><i class="fa fa-key" aria-hidden="true"></i> שינוי סיסמה</a>
<a class="button" title="יציאה" href="/login?logout=<?php echo $_SESSION['signature']; ?>"><i class="fa fa-times-circle-o" aria-hidden="true"></i> יציאה</a>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>