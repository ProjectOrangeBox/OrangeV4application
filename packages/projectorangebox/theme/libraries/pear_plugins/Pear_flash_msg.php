<?php

class Pear_flash_msg extends \Pear_plugin
{
	public function __construct()
	{
		if (!config('page.usingBundle')) {
			ci('page')
				->css('/theme/orange/assets/plugins/flash-msg/flash-msg'.PAGE_MIN.'.css')
				->js('/theme/orange/assets/plugins/flash-msg/jquery.bootstrap.flash-msg'.PAGE_MIN.'.js');
		}

		$msgs = [];

		/* get the content from the view variable */
		$payload = ci('load')->get_var('wallet_messages');

		if (is_array($payload)) {
			$types = [
				'green'   => 'success',
				'red'     => 'danger',
				'yellow'  => 'warning',
				'blue'    => 'info',
				'success' => 'success',
				'error'   => 'danger',
				'block'   => 'warning',
				'info'    => 'info',
				'primary' => 'primary',
				'warning' => 'warning',
				'danger'  => 'danger',
			];

			if (is_array($payload['messages'])) {
				foreach ($payload['messages'] as $msg) {
					$msg = ['text'=>$msg['msg'],'stay'=>$msg['sticky'],'type'=>$types[$msg['type']]];

					if ($msg['sticky'] == true) {
						$msg['staytime'] = ($payload['pause_for_each'] * ($payload['initial_pause']++));
					}

					$msgs[] = $msg;
				}
			}
		}

		ci('page')->js_variable('messages', $msgs);

		return $msgs;
	}
}
