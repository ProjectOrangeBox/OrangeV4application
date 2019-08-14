<?php

define('NOVALUE','#PLEASE_THROW_AN_EXCEPTION#');

/* new global functions */
require 'Functions.php';

/* orange static functions to keep them out of "global" namespace */
require 'Orange.php';

/* global wrappers functions */
require 'Wrappers.php';

require_once BASEPATH.'core/CodeIgniter.php';