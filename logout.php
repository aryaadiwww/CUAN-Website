<?php
session_start();
session_unset();
session_destroy();
// Reset sessionStorage musik dengan JavaScript
echo '<script>sessionStorage.removeItem("cuan-music-time"); sessionStorage.removeItem("cuan-music-playing"); window.location.href="index.html";</script>';
exit();
?>
