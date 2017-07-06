<?php
/***********************************************************************************/
/************This file defines the constants used across the application************/
/***********************************************************************************/

/**************************POST Request Types**************************************/

define("REQUEST_TYPE_NEW_MATCH_REQUEST", 1);
define("REQUEST_TYPE_CANCEL_PREV_REQUEST", 2);
define("REQUEST_TYPE_MODIFY_PREV_RQUEST", 3);
define("REQUEST_TYPE_UPDATE_PROFILE_REQUEST", 4);
define("REQUEST_TYPE_SIGNUP_REQUEST", 5);
define("REQUEST_TYPE_LOGIN_REQUEST", 6);
define("REQUEST_TYPE_FORGOT_PASSWORD_REQUEST", 7);

/**********************************Deal Types**************************************/

define("DEAL_TYPE_X_UNITS_Y_UNITS", 1);
define("DEAL_TYPE_X_UNITS_Y_PERCENT", 2);
define("DEAL_TYPE_X_AMOUNT_Y_AMOUNT", 3);
define("DEAL_TYPE_X_AMOUNT_Y_PERCENT", 4);

?>