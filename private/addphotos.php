<?php
require $_SERVER['DOCUMENT_ROOT'] . '/private/authenticate.php';
if (!isset($_SESSION['albumId']))
{
	$mysqli->close();
	header('Location: /private');
	exit;
}
?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="fullwidth">
<div id="contentInner">
<script type="text/template" id="qq-template-gallery">
<div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="משוך קבצים לכאן">
<div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
</div>
<div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
<span class="qq-upload-drop-area-text-selector"></span>
</div>
<div class="qq-upload-button-selector qq-upload-button">
<div>העלה קבצים</div>
</div>
<span class="qq-drop-processing-selector qq-drop-processing">
<span>מעבד קבצים...</span>
<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
</span>
<ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
<li>
<span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
<div class="qq-progress-bar-container-selector qq-progress-bar-container">
<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
</div>
<span class="qq-upload-spinner-selector qq-upload-spinner"></span>
<div class="qq-thumbnail-wrapper">
<img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
</div>
<button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
<button type="button" class="qq-upload-retry-selector qq-upload-retry">
<span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
נסה שוב
</button>
<div class="qq-file-info">
<div class="qq-file-name">
<span class="qq-upload-file-selector qq-upload-file"></span>
<span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
</div>
<input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
<span class="qq-upload-size-selector qq-upload-size"></span>
<button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
<span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
</button>
<button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
<span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
</button>
<button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
<span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
</button>
</div>
</li>
</ul>
<dialog class="qq-alert-dialog-selector">
<div class="qq-dialog-message-selector"></div>
<div class="qq-dialog-buttons">
<button type="button" class="qq-cancel-button-selector">סגור</button>
</div>
</dialog>
<dialog class="qq-confirm-dialog-selector">
<div class="qq-dialog-message-selector"></div>
<div class="qq-dialog-buttons">
<button type="button" class="qq-cancel-button-selector">לא</button>
<button type="button" class="qq-ok-button-selector">כן</button>
</div>
</dialog>
<dialog class="qq-prompt-dialog-selector">
<div class="qq-dialog-message-selector"></div>
<input type="text">
<div class="qq-dialog-buttons">
<button type="button" class="qq-cancel-button-selector">ביטול</button>
<button type="button" class="qq-ok-button-selector">אישור</button>
</div>
</dialog>
</div>
</script>
<div id="fine-uploader-gallery"></div>	
<p><a title="עבור לעדכון תמונות" href="/private/updatephotos.php?id=<?php echo $_SESSION['albumId']; ?>">עבור לעדכון תמונות</a></p>
<p><a title="עבור להוספת סרטונים" href="/private/addvideos.php?id=<?php echo $_SESSION['albumId']; ?>">עבור להוספת סרטונים</a></p>
<p><a title="עבור לעדכון סרטונים" href="/private/updatevideos.php?id=<?php echo $_SESSION['albumId']; ?>">עבור לעדכון סרטונים</a></p>
<p><a title="עבור לעדכון אלבומים" href="/private/updatealbums">עבור לעדכון אלבומים</a></p>
<p><a title="חזרה לעמוד הניהול" href="/private">חזרה לעמוד הניהול</a></p>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
<script>
$('#fine-uploader-gallery').fineUploader({
	template: 'qq-template-gallery',
	request: {
		endpoint: 'upload/endpoint.php'
	},
	thumbnails: {
		placeholders: {
			waitingPath: '/images/waiting-generic.png',
			notAvailablePath: '/images/not_available-generic.png'
		}
	},
	validation: {
		allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
	}
});
</script>
</body>
</html>