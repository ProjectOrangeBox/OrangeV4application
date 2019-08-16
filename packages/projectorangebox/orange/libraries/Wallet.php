<?php

namespace projectorangebox\orange\library;

/**
 * Orange
 *
 * An open source extensions for CodeIgniter 3.x
 *
 * This content is released under the MIT License (MIT)
 * Copyright (c) 2014 - 2019, Project Orange Box
 */

/**
 * Authorization class.
 *
 * Handles login, logout, refresh user data
 *
 * @package CodeIgniter / Orange
 * @author Don Myers
 * @copyright 2019
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 * @version v2.0
 * @filesource
 *
 * @uses # \session - CodeIgniter Session
 * @uses # \event - Orange Event
 * @uses # \load - CodeIgniter Loader
 *
 * @config sticky types `['red','danger','warning','yellow']`
 * @config initial pause `3`
 * @config pause for each `1000`
 *
 */
class Wallet
{
	/**
	 * Storage of redirect messages
	 *
	 * @var Array
	 */
	protected $redirectMessages = [];

	/**
	 * Session key for wallet messages
	 *
	 * @var String
	 */
	protected $msgKey = 'internal::wallet::msg';

	/**
	 * View variable to place the redirect messages
	 * This can then be used by the views javascript to display the messages
	 * or for further processing
	 *
	 * @var String
	 */
	protected $viewVariable = 'wallet_messages';

	/**
	 * Servers HTTP Referer
	 *
	 * @var string
	 */
	protected $httpReferer;

	/**
	 * Local reference of wallet configuration
	 *
	 * @var Array
	 */
	protected $config;

	/**
	 * Local reference of CodeIgniter Loader
	 *
	 * @var Object
	 */
	protected $load;

	/**
	 * Local reference of CodeIgniter Session
	 *
	 * @var Object
	 */
	protected $session;

	/**
	 * Local reference of Orange Event
	 *
	 * @var \Event
	 */
	protected $event;

	/**
	 * $stickyTypes
	 *
	 * @var array
	 */
	protected $stickyTypes = [];

	/**
	 * $initialPause
	 *
	 * @var integer
	 */
	protected $initialPause = 3;

	/**
	 * $pauseForEach
	 *
	 * @var integer
	 */
	protected $pauseForEach = 1000;

	/**
	 *
	 * Constructor
	 *
	 * @access public
	 *
	 * @param array $config []
	 *
	 */
	public function __construct(array &$config=[])
	{
		$this->config = &$config;

		$this->session = ci('session');
		$this->event = ci('event');
		$this->load = ci('load');

		/* where did we come from? */
		$this->httpReferer = ci('input')->server('HTTP_REFERER');

		/* What msg types should be considered "stickey" */
		$this->stickyTypes = $this->config['sticky types'] ?? ['red','danger','warning','yellow'];
		$this->initialPause = $this->config['initial pause'] ?? 3;
		$this->pauseForEach = $this->config['pause for each'] ?? 1000;

		/* set the view variable if any messages are available */
		$currentMessages = $this->session->flashdata($this->msgKey);

		if (is_array($currentMessages)) {
			$this->setViewVariable($currentMessages);
		}

		log_message('info', 'Orange Wallet Class Initialized');
	}

	/**
	 *
	 * Add a flash message to the current page as javascript variable
	 * Add a flash message as a session flash message and redirect
	 *
	 * @access public
	 *
	 * @param string $msg
	 * @param string $type yellow
	 * @param $redirect null, string, or true. if true http referring page will be used.
	 *
	 * @return \Wallet
	 *
	 * #### Example
	 * ```php
	 * ci('wallet')->msg('Oh No!','yellow');
	 * ci('wallet')->msg('oH No!','red','/folder/new');
	 * ```
	 */
	public function msg(string $msg = '', string $type = 'yellow', $redirect = null) : Wallet
	{
		/* is this type sticky? - use names not colors - colors support for legacy code */
		$sticky = in_array($type, $this->stickyTypes);

		/* trigger a event incase they need to do something */
		$this->event->trigger('wallet.msg', $msg, $type, $sticky, $redirect);

		/* is this a redirect */
		if (is_string($redirect)) {
			$this->redirect($msg, $type, $sticky, $redirect);
		} elseif ($redirect === true) {
			$this->redirect($msg, $type, $sticky, $this->httpReferer);
		} else {
			$this->add2page($msg, $type, $sticky);
		}

		return $this;
	}

	/**
	 *
	 * Add multiple messages at one time
	 *
	 * @access public
	 *
	 * @param array $array
	 * @param string $type blue
	 *
	 * @return \Wallet
	 *
	 * #### Example
	 * ```php
	 * ci('wallet')->msgs(['Whoops!','Defcon 1'=>'red','Info']);
	 * ```
	 */
	public function msgs(array $array, string $type='yellow') : Wallet
	{
		foreach ($array as $a=>$b) {
			if (is_numeric($a)) {
				$this->msg($b, $type);
			} else {
				$this->msg($a, $b);
			}
		}

		return $this;
	}

	/**
	 *
	 * Add a message and redirect
	 *
	 * @access protected
	 *
	 * @param $msg
	 * @param $type
	 * @param $sticky
	 * @param $redirect
	 *
	 * @return void
	 *
	 */
	protected function redirect(string $msg, string $type, bool $sticky, string $redirect) : void
	{
		/* add another message to any that might already be on there */
		$this->redirectMessages[md5(trim($msg))] = ['msg' => trim($msg), 'type' => $type, 'sticky' => $sticky];

		/* store this in a session variable */
		$this->session->set_flashdata($this->msgKey, $this->redirectMessages);

		redirect($redirect);
	}

	/**
	 *
	 * Add message to the current pages view javascript variable
	 *
	 * @access protected
	 *
	 * @param string $msg
	 * @param string $type
	 * @param bool $sticky
	 *
	 * @return \Wallet
	 *
	 */
	protected function add2page(string $msg, string $type, bool $sticky) : Wallet
	{
		/* add to the current wallet messages */
		$currentMsgs = $this->getViewVariable();

		/* add messages */
		$currentMsgs[md5(trim($msg))] = ['msg' => trim($msg), 'type' => $type, 'sticky' => $sticky];

		/* put back in view variable */
		$this->setViewVariable($currentMsgs);

		return $this;
	}

	/**
	 *
	 * Get the page view variable contents
	 *
	 * @access protected
	 *
	 * @return Array
	 *
	 */
	protected function getViewVariable() : array
	{
		/* get the current messages */
		$walletMessages = $this->load->get_var($this->viewVariable);

		/* we only need the messages */
		return (array)$walletMessages['messages'];
	}

	/**
	 *
	 * Set the page view variable
	 *
	 * @access protected
	 *
	 * @param $messages
	 *
	 * @return \Wallet
	 *
	 */
	protected function setViewVariable(array $messages) : Wallet
	{
		/* get any flash messages in the session and add them to the view data */
		$this->load->vars([$this->viewVariable => [
			'messages'       => $messages,
			'initial_pause'  => $this->initialPause,
			'pause_for_each' => $this->pauseForEach,
		]]);

		return $this;
	}
} /* end class */
