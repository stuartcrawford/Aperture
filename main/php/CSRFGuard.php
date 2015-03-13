<?php
	function store_in_session($value)
	{
		if (isset($_SESSION))
		{
			$_SESSION['CSRFToken']=$value;
		}
	}
	
	function unset_session()
	{
		unset($_SESSION['CSRFToken']);
	}
	
	function get_from_session()
	{
		if (isset($_SESSION['CSRFToken']))
		{
			return $_SESSION['CSRFToken'];
		}
		else
		{
			return ' ';
		}
	}

	function csrfguard_generate_token()
	{
		if (!isset($_SESSION['CSRFToken']))
		{
			if (function_exists("hash_algos") and in_array("sha512",hash_algos()))
			{
				$token=hash("sha512",mt_rand(0,mt_getrandmax()));
			}
			else
			{
				$token=' ';
				for ($i=0;$i<128;++$i)
				{
					$r=mt_rand(0,35);
					if ($r<26)
					{
						$c=chr(ord('a')+$r);
					}
					else
					{ 
						$c=chr(ord('0')+$r-26);
					} 
					$token.=$c;
				}
			}
			store_in_session($token);
		}
		return $_SESSION['CSRFToken'];
	}
	
	function csrfguard_validate_token($token_value)
	{
		$token=get_from_session();
		if ($token===$token_value)
		{
			$result=true;
		}
		else
		{ 
			$result=false;
		} 
		return $result;
	}

	function csrfguard_replace_forms($form_data_html)
	{
		$count=preg_match_all("/<form(.*?)>(.*?)<\\/form>/is",$form_data_html,$matches,PREG_SET_ORDER);
		if (is_array($matches))
		{
			foreach ($matches as $m)
			{
				if (strpos($m[1],"nocsrf")!==false) { continue; }
				$token=csrfguard_generate_token();
				$form_data_html=str_replace($m[0],
					"<form{$m[1]}>
	<input type='hidden' name='CSRFToken' value='{$token}' />{$m[2]}</form>",$form_data_html);
			}
		}
		return $form_data_html;
	}

	function csrfguard_inject()
	{
		$data=ob_get_clean();
		$data=csrfguard_replace_forms($data);
		echo $data;
	}
	
	function csrfguard_regenerate_token() // Call this on successful login and logout
	{
		unset_session();
		csrfguard_generate_token();
	}
?>