<?php
defined('BASEPATH') OR exit('No direct script access allowed');

   /*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

   /*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

   /*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

   /*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


defined('TOPUP')                  OR define("TOPUP",'Wallet Top Up');
defined('WITHDRAW')               OR define("WITHDRAW",'Withdraw');
defined('BOOKED')                 OR define("BOOKED",'Booked a Service');
defined('COMPLETE_PROVIDER')      OR define("COMPLETE_PROVIDER",'Complete the Service');
defined('PROVIDER_REJECT')        OR define("PROVIDER_REJECT",'Service Amount was Refunded');

/* @Leo: Service Amount Types and Measurements */
$serviceAmountType = [
   "Fixed" => "Fixed",
   "Hourly" => "Hourly",
   "Monthly" => "Monthly"
];
defined('SERVICE_AMOUNT_TYPES')    OR define("SERVICE_AMOUNT_TYPES", $serviceAmountType);
defined('FIXED')                   OR define("FIXED",'Fixed');
defined('HOURLY')                  OR define("HOURLY",'Hourly');
defined('MONTHLY')                 OR define("MONTHLY",'Monthly');
$serviceMeasurement = [
   "none" => "Not Used",
   "per sqft" => "Per Square Feet",
   "tones" => "Tones",
   "cm" => "Centimeter",
   "mm" => "Millimeter",
   "height" => "Height",
   "length" => "Length",
   "width" => "Width",
];
defined('SERVICE_MEASUREMENTS')    OR define("SERVICE_MEASUREMENTS", $serviceMeasurement);
defined('NONE')                 OR define("NONE",'none');
/* @Leo: Coupons Type */
$couponTypes = [
   "amount" => "Discount By Amount",
   "percentage" => "Discount By Percentage"
];
defined('COUPON_TYPES')    OR define("COUPON_TYPES", $couponTypes);
defined('AMOUNT')          OR define("AMOUNT",'amount');
defined('PERCENTAGE')      OR define("PERCENTAGE",'percentage');
/* ------------------------------------------- */


$pay_methods = [
   "One-off" => "One off",
   "Per sqft" => "Per Sqft",
   "Fixed" => "Fixed-price",
   "Hourly" => "Hourly",
   "Monthly" => "Monthly",
   "Weekly" => "Weekly",
   "2-Weekly" => "2 Weekly",
   "Milestones" => "Milestones",
   "Options-to-show" => "Options to show price or not",
];
defined('C_JOB_TYPES')              OR define("C_JOB_TYPES", $pay_methods);

$measurements = [
   "tones" => "tones",
   "cm" => "cm",
   "mm" => "mm",
   "Hight" => "Hight",
   "Length" => "Length",
   "Wide" => "Wide",
];
defined('C_MEASUREMENTS')        OR define('C_MEASUREMENTS', $measurements);

$contracts = [
   "3" => "3 months contract",
   "6" => "6 months contract",
   "12" => "12 months contract"
];
defined('C_CONTRACTS')        OR define('C_CONTRACTS', $measurements);

# add by maksimU : For regist user
define('C_YOUARE_FREELANCER'     , 1);
define('C_YOUARE_SHOP'           , 2);
define('C_YOUARE_LOCALJOB'       , 3);
define('C_YOUARE_SOLELYVENDER'   , 4);
define('C_YOUARE_SUBCONTRACTOR'  , 5);
define('C_YOUARE_SOLETRADER'     , 6);
define('C_YOUARE_BUSINESS'       , 7);
define('C_YOUARE_EMPLOYEE'       , 8);
define('C_YOUARE_USER'           , 9);
define('C_YOUARE_ORGANIZATION'   ,10);
define('C_YOUARE_STAFF'          ,11);
define('C_YOUARE_PARTNER'        ,12);
define('C_YOUARE_SELF_EMPLOYED'  ,13);

$youare_applingas = [
   //"0" => "Select",
   C_YOUARE_FREELANCER => "Freelancer",
   C_YOUARE_SHOP => "Products vendon(shop)",
   C_YOUARE_LOCALJOB => "Local jobs and service",
   C_YOUARE_SOLELYVENDER => "Solely Online vender",
   C_YOUARE_SUBCONTRACTOR => "Sub-Contractor",
   C_YOUARE_SOLETRADER => "Sole trader",
   C_YOUARE_BUSINESS => "Business",
   C_YOUARE_EMPLOYEE => "Employees",
   C_YOUARE_SELF_EMPLOYED => "Self-Employed",
   C_YOUARE_USER => "Buyer (Users)",
   C_YOUARE_ORGANIZATION => "Organization",
   C_YOUARE_PARTNER => "Partner"
];
defined('C_APPLINGAS')        OR define('C_APPLINGAS', $youare_applingas);


# book service status
define('BS_PENDING',             1);
define('BS_INPROGRESS',          2);
define('BS_COMPLETED_PROVIDER',  3);
define('BS_ACCEPTED',            4);
define('BS_REJECTED',            5);
define('BS_COMPLETED',           6);
define('BS_CANCELLED',           7);

defined('C_WEBSITENAME')         OR define('C_WEBSITENAME', 'tazzerclean');
defined('C_FRONTLOGO')           OR define('C_FRONTLOGO', 'img/logo.png');
defined('C_FAVICON')             OR define('C_FAVICON', 'img/favicon.png');
defined('C_DEFAILTCOLOR')        OR define('C_DEFAILTCOLOR', '#5d2566');



# organization book service status
define('ORG_BS_PENDING',             1);
define('ORG_BS_INPROGRESS',          2);
define('ORG_BS_ACCEPTED',            3);
define('ORG_BS_REJECTED',            4);
define('ORG_BS_COMPLETED',           5);
define('ORG_BS_CANCELLED',           6);

# fees type (reference fees table)
define('TRANSACTION_FEE'     , 1);
define('SALES_FEE'           , 2);
define('COMMISSION'          , 3);

# request ads status
define('ADS_PENDING',             1);
define('ADS_INPROGRESS',          2);
define('ADS_COMPLETED',           3);
define('ADS_REJECTED',            4);

define('GOOGLE_MAPAPI_KEY','AIzaSyD6Cx1cmZX5lyQON7PCLwJLK36QpLI0SUo');
# maksimU end
