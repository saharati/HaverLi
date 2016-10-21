<?php require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="private fullwidth">
<div id="contentInner">
<ul>
<li>
<h2>משתמשים</h2>
<ul>
<li><a title="הוספת משתמשים" href="/private/addusers.php">הוספת משתמשים</a></li>
<li><a title="עדכון משתמשים" href="/private/updateusers.php">עדכון משתמשים</a></li>
</ul>
</li>
<li>
<h2>דף הבית</h2>
<ul>
<li><a title="הוספת תמונות" href="/private/addimages.php">הוספת תמונות</a></li>
<li><a title="עדכון תמונות" href="/private/updateimages.php">עדכון תמונות</a></li>
</ul>
</li>
<li>
<h2>דף האודות</h2>
<ul>
<li><a title="הוספת מתנדבים" href="/private/addvolunteers.php">הוספת מתנדבים</a></li>
<li><a title="עדכון מתנדבים" href="/private/updatevolunteers.php">עדכון מתנדבים</a></li>
<li><a title="עדכון תוכן" href="/private/updateabout.php">עדכון תוכן</a></li>
</ul>
</li>
<li>
<h2>מאגר תמונות</h2>
<ul>
<li><a title="הוספת תמונות" href="/private/adddatabase.php">הוספת תמונות</a></li>
<li><a title="עדכון תמונות" href="/private/updatedatabase.php">עדכון תמונות</a></li>
</ul>
</li>
<li>
<h2>אישי</h2>
<ul>
<li><a title="שינוי סיסמה" href="/private/changepassword.php">שינוי סיסמה</a></li>
<li><a title="יציאה" href="/login?logout=<?php echo $_SESSION['signature']; ?>">יציאה</a></li>
</ul>
</li>
</ul>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>