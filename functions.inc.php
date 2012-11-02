<?php

global $amp_conf;

class motif_conf {

	function get_filename() {
        $files = array(
			'motif.conf',
            'xmpp.conf',
			'rtp.conf',
            'extensions_additional.conf'
        );
        return $files;
	}

	function generateConf($file) {
        global $version,$amp_conf;

        if(!file_exists($amp_conf['ASTETCDIR'] . '/motif_custom.conf')) {
            touch($amp_conf['ASTETCDIR'] . '/motif_custom.conf');
        }

        if(!file_exists($amp_conf['ASTETCDIR'] . '/xmpp_custom.conf')) {
            touch($amp_conf['ASTETCDIR'] . '/xmpp_custom.conf');
        }

        if(file_exists($amp_conf['ASTETCDIR'] . '/xmpp.conf') && !file_exists($amp_conf['ASTETCDIR'] . '/xmpp.conf.bak')) {
            copy($amp_conf['ASTETCDIR'] . '/xmpp.conf', $amp_conf['ASTETCDIR'] . '/xmpp.conf.bak');
        }

        if(file_exists($amp_conf['ASTETCDIR'] . '/motif.conf') && !file_exists($amp_conf['ASTETCDIR'] . '/motif.conf.bak')) {
            copy($amp_conf['ASTETCDIR'] . '/motif.conf', $amp_conf['ASTETCDIR'] . '/motif.conf.bak');
        }

        switch ($file) {
            case 'motif.conf':
                return $this->generate_motif_conf($version);
                break;
            case 'xmpp.conf':
                return $this->generate_xmpp_conf($version);
                break;
			case 'rtp.conf':
            	return $this->generate_rtp_conf($version);
            	break;
            case 'extensions_additional.conf':
                return $this->generate_extensions_conf($version);
                break;
        }
    }

    function generate_motif_conf($ast_version) {
        global $astman;

		$sql = 'SELECT * FROM `motif`';
		$accounts = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);

        $output = '';

		foreach($accounts as $list) {
			$context = str_replace('@','',str_replace('.','',$list['username']));
			$output .= "[g".$context."]\n";
			$output .= "context=incoming-motif\n";
			$output .= "disallow=all\n";
			$output .= "allow=ulaw\n";
			$output .= "connection=g".$context."\n";
		}

		/*
        $response = $astman->send_request('Command', array('Command' => 'module unload chan_motif.so'));
        $response = $astman->send_request('Command', array('Command' => 'module load chan_motif.so'));
		*/

		return $output;
	}

	function generate_xmpp_conf($ast_version) {
		global $astman,$db;

		$sql = 'SELECT * FROM `motif`';
		$accounts = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);

		$output = "[general]\n";
		foreach($accounts as $list) {
			$context = str_replace('@','',str_replace('.','',$list['username']));

			$output .= "[g".$context."]\n";
			$output .= "type=client\n";
			$output .= "serverhost=talk.google.com\n";

			$output .= "username=".$list['username']."\n";
			$output .= "secret=".$list['password']."\n";

			$output .= "priority=1\n";
			$output .= "port=5222\n";
			$output .= "usetls=yes\n";
			$output .= "usesasl=yes\n";
			$output .= "status=available\n";
			$output .= "statusmessage=\"I am available\"\n";
			$output .= "timeout=5\n";
		}

		/*
		$response = $astman->send_request('Command', array('Command' => 'module unload res_xmpp.so'));
        $response = $astman->send_request('Command', array('Command' => 'module load res_xmpp.so'));
		*/

		return $output;
	}

	function generate_rtp_conf($ast_version) {
		global $astman;

		$output = "[general]\n";
		$output .= "rtpstart=10000\n";
		$output .= "rtpend=20000\n";
		$output .= "icesupport=yes\n";

		//$response = $astman->send_request('Command', array('Command' => 'module unload res_rtp_asterisk.so'));
        //$response = $astman->send_request('Command', array('Command' => 'module load res_rtp_asterisk.so'));

		return $output;
	}

	function generate_extensions_conf($ast_version) {
        global $ext;

		$sql = 'SELECT * FROM `motif`';
		$accounts = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);


		$incontext = "incoming-motif";
		$address = 's';

		$ext->add($incontext, $address, '1', new ext_noop('Receiving GoogleVoice call'));

		$ext->add($incontext, $address, '', new ext_noop('${EXTEN}'));

		$ext->add($incontext, $address, '', new ext_setvar('CALLERID(name)', '${CUT(CALLERID(name),@,1)}'));
        $ext->add($incontext, $address, '', new ext_gotoif('$["${CALLERID(name):0:2}" != "+1"]', 'nextstop'));
        $ext->add($incontext, $address, '', new ext_setvar('CALLERID(name)', '${CALLERID(name):2}'));
        $ext->add($incontext, $address, 'nextstop', new ext_gotoif('$["${CALLERID(name):0:1}" != "+"]', 'notrim'));
        $ext->add($incontext, $address, '', new ext_setvar('CALLERID(name)', '${CALLERID(name):1}'));
        $ext->add($incontext, $address, 'notrim', new ext_setvar('CALLERID(number)', '${CALLERID(name)}'));

		/*
		$ext->add($incontext, $address, '', new ext_setvar('crazygooglecid', '${CALLERID(name)}'));
		$ext->add($incontext, $address, '', new ext_setvar('stripcrazysuffix', '${CUT(crazygooglecid,@,1)})'));
		$ext->add($incontext, $address, '', new ext_setvar('CALLERID(all)', '${stripcrazysuffix}'));
		*/

		$ext->add($incontext, $address, '', new ext_wait('1'));
        $ext->add($incontext, $address, '', new ext_answer(''));
        $ext->add($incontext, $address, '', new ext_senddtmf('1'));

		$ext->add($incontext, $address, '', new ext_goto('1', $accounts[0]['phonenum'], 'from-trunk'));

		$ext->add($incontext, 'h', '', new ext_hangup(''));


		return $ext->generateConf();


	}

}
