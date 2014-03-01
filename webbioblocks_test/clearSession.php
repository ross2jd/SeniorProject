<?php
// clear the session
function clearSession()
{
    session_unset();
    session_destroy();
    return;
}
?>