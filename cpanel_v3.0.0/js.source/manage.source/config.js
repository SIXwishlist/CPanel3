
/*! config */

/* global & constants */
var g_request_url  = "./ajax.php";
var g_template_url = "./template.php";

// globals
var g_countries = [];

var g_index = 0;
var g_count = 10;
var g_search_object = {};


// General Constants
var STATUS_ICON_TRUE  = 1;
var STATUS_ICON_FALSE = -1;
var STATUS_ICON_INFO  = 1;


//Other constants
var GENDER_MALE   = 1;
var GENDER_FEMALE = 2;
var GENDER_ANY    = 3;

var SOURCE_LIST   = 1;
var SOURCE_SEARCH = 2;


//Error Constants
var VERIFIED     = 1;
var SERVER_ERROR = 0;
var NOT_VERIFIED = -1;
var NOT_EXIST    = -2;
var CODE_ERROR   = -3;
var BLOCKED      = -4;
var INPUT_ERROR  = -5;

var REQUEST_NOT_FOUND =   -10;

var g_loading_div = '<div id="loading-container"><i class="fa fa-spin fa-refresh     fa-3x fa-fw"></i></div>';

//############################################################################//

//Web Based Constants

var USER_TYPE_MASTER       = 1;
var USER_TYPE_ORG          = 2;
var USER_TYPE_CHECKER      = 3;
var USER_TYPE_ENTRY        = 4;

var USER_TYPE_INACTIVE     = 0;
var USER_TYPE_ACTIVE       = 1;
var USER_TYPE_BLOCKED      = 2;
var USER_TYPE_DELETED      = 3;


var ORG_STATUS_HOLD    = 0;
var ORG_STATUS_EXPIRED = 1;
var ORG_STATUS_TRIAL   = 2;
var ORG_STATUS_ACTIVE  = 3;

var ORG_TYPE_UNKNOWN    = 0;
var ORG_TYPE_UNIVERSITY = 1;
var ORG_TYPE_COLLAGE    = 2;
var ORG_TYPE_INSTITUTE  = 3;
var ORG_TYPE_TRAINING   = 4;
var ORG_TYPE_SCHOOL     = 5;
var ORG_TYPE_OTHER      = 6;

var ORG_LOGO_WIDTH  = 200;
var ORG_LOGO_HEIGHT = 200;

var CAPATCHA_TRIALS =  2;

var REQUEST_NOT_FOUND  = -10;
var CAPATCHA_REQUIRED  = -11;
var CAPATCHA_INCORRECT = -12;

var SERVER_ERROR    = -20;
var USER_NOT_EXIST  = -21;
var ACCOUNT_EXPIRED = -22;