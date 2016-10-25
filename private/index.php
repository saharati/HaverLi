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
<div class="right">
<ul>
<li>
<h2>דף הבית</h2>
<ul>
<li><a title="הוספת תמונות" href="/private/addimages.php">הוספת תמונות</a></li>
<li><a title="עדכון תמונות" href="/private/updateimages.php">עדכון תמונות</a></li>
</ul>
</li>
<li>
<h2>אודות</h2>
<ul>
<li><a title="הוספת מתנדבים" href="/private/addvolunteers.php">הוספת מתנדבים</a></li>
<li><a title="עדכון מתנדבים" href="/private/updatevolunteers.php">עדכון מתנדבים</a></li>
<li><a title="עדכון תוכן" href="/private/updateabout.php">עדכון תוכן</a></li>
</ul>
</li>
<li>
<h2>איך ניתן לעזור</h2>
<ul>
<li><a title="הוספת תמונות" href="/private/addhelpimages.php">הוספת תמונות</a></li>
<li><a title="עדכון תמונות" href="/private/updatehelpimages.php">עדכון תמונות</a></li>
<li><a title="הוספת תוכן" href="/private/addhelp.php">הוספת תוכן</a></li>
<li><a title="עדכון תוכן" href="/private/updatehelppage.php">עדכון תוכן</a></li>
</ul>
</li>
<li>
<h2>לוח מודעות</h2>
<ul>
<li><a title="הוספת תמונות" href="/private/addboard.php">הוספת תמונות</a></li>
<li><a title="עדכון תמונות" href="/private/updateboard.php">עדכון תמונות</a></li>
</ul>
</li>
<li>
<h2>צור קשר</h2>
<ul>
<li><a title="הוספת דרכי תקשורת" href="/private/addcontact.php">הוספת דרכי תקשורת</a></li>
<li><a title="עדכון דרכי תקשורת" href="/private/updatecontact.php">עדכון דרכי תקשורת</a></li>
</ul>
</li>
<li>
<h2>משפחות מאושרות</h2>
<ul>
<li><a title="הוספת משפחות" href="/private/addfamilies.php">הוספת משפחות</a></li>
<li><a title="עדכון משפחות" href="/private/updatefamilies.php">עדכון משפחות</a></li>
</ul>
</li>
</ul>
</div>
<div class="left">
<ul>
<li>
<h2>גזעים</h2>
<ul>
<li><a title="הוספת גזעים" href="/private/addbreeds.php">הוספת גזעים</a></li>
<li><a title="עדכון גזעים" href="/private/updatebreeds.php">עדכון גזעים</a></li>
</ul>
</li>
<li>
<h2>פינת אימוץ</h2>
<ul>
<li><a title="הוספת אלבומים" href="/private/addalbums.php">הוספת אלבומים</a></li>
<li><a title="עדכון אלבומים" href="/private/updatealbums.php">עדכון אלבומים</a></li>
</ul>
</li>
<li>
<h2>מאגר תמונות לטקסט</h2>
<ul>
<li><a title="הוספת תמונות" href="/private/adddatabase.php">הוספת תמונות</a></li>
<li><a title="עדכון תמונות" href="/private/updatedatabase.php">עדכון תמונות</a></li>
</ul>
</li>
<li>
<h2>משתמשים</h2>
<ul>
<li><a title="הוספת משתמשים" href="/private/addusers.php">הוספת משתמשים</a></li>
<li><a title="עדכון משתמשים" href="/private/updateusers.php">עדכון משתמשים</a></li>
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
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>