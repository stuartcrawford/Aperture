<?php
/**
 * Validates a HTML Form it creates all javascript code
 * you must include in your viewer validate.js and then add the parse output to the page
 * Example
 * <code>
 * $formvalidator = new formvalidator('contact_form_id');
 * $formvalidator -> validateEmail('email_input_id','Not a valid email !');
 * $formvalidator -> notEmpty('name_input_id','You didn/'t enter your name!');
 * $view -> validation_js_code -> $formvalidator -> parse();//generates javascript code for the validation
 * </code>
 */
class formValidator//form javascript validation generation
{
	/**
	 * Holds the generated javascript code
	 *
	 * @var string;
	 */
	var $js;
	//var $error_color = '#FFFFBC';
	var $error_color = '#FFFCDB';
	var $error_count = 1; // Use zero for unlimited errors in js popup
	var $field_count = 0; // Use zero for highlighting an unlimited number of inputs
	var $error_message;
	var $exfunction;
	var $stop; // the quotes around it in the code are required to take false from php instead of ''
	/**
	 * Sets the form javascript id
	 *
	 * @param unknown_type $formname
	 */
	function __construct($formname,$formbutton)
	{
		$this->formName = $formname;
		$this->formButton = $formbutton;
		$this->js = 'function validate'.$formname.'(formname) {';
	}
	/**
	* Set input background color when the input does not validate
	*/
	function setColor($color)
	{
		$this -> error_color = $color;
	}
	/**
	 * Calls a javascript function to perform ajax validation or submission
	 * First parameter is the function to call second is whether or not the form submission should be stopped
	 *
	 * @param string $func
	 * @param boolean $s
	 */
	function callFunction($func,$s) // s is true or false
	{
		$this -> exfunction = $func;
		$this -> stop = $s;
	}
	/**
	 * Checks if the input contains a number, if it dosen't it displays error message
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function validateNumber($inputname,$error_message)
	{
		$this->js.='if (!isNumeric(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset( $_POST[$inputname] ) && (!preg_match("/^-?([0-9])*\.?([0-9])+$/", $_POST[$inputname])) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Checks if the input contains a number with no more than the specified number of decimal places, if it dosen't it displays error message
	 *
	 * @param string $inputname
	 * @param integer $dp
	 * @param string $errormessage
	 */
	function validateDecimal($inputname,$dp,$error_message) // dp must be an integer of 0 or greater
	{
		$this->js.='if (!isRounded(formname.'.$inputname.',"'.$dp.'","'.$error_message.'")) return false;';
		if ( isset( $_POST[$inputname] ) && ($dp != "" && round($_POST[$inputname],$dp) != $_POST[$inputname]) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Checks if the input contains a number within the specified range, if it dosen't it displays error message
	 *
	 * @param string $inputname
	 * @param integer $min
	 * @param integer $max
	 * @param string $error_message1
	 * @param string $error_message2
	 */
	function validateRange($inputname,$min,$max,$error_message1,$error_message2) // min and max may be null, errormessage1 and errormessage2 may be null if the corresponding parameter is set to null
	{
		$this->js.='if (!inRange(formname.'.$inputname.',"'.$min.'","'.$max.'","'.$error_message1.'","'.$error_message2.'")) return false;'; // quotes must be added to min and max to allow null to be entered unquoted
		if ( isset( $_POST[$inputname] ) && ($min != "" && $_POST[$inputname] < $min) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message1.'<br>';
		}
		elseif ( isset( $_POST[$inputname] ) && ($max != "" && $_POST[$inputname] > $max) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message2.'<br>';
		}
	}
	/**
	 * Checks if the input contains a number in pounds, if it dosen't it displays error message
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function validatePounds($inputname,$error_message)
	{
		$this->js.='if (!inPounds(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && (!preg_match("/^\d+(\.\d{2})?$/", $_POST[$inputname])) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Validate email first parameter contains the input id, the second the message that will be displayed if the email is not valid
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function validateEmail($inputname,$error_message)
	{
		$this->js.='if (!isEmail(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST[$inputname])) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Validate telephone first parameter contains the input id, the second the message that will be displayed if the email is not valid
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function validateTelephone($inputname,$error_message)
	{
		$this->js.='if (!isTelephone(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset( $_POST[$inputname] ) && (!preg_match("/^-?([0-9])*\.?([0-9])+$/", str_replace(' ','',$_POST[$inputname])) || strlen(str_replace(' ','',$_POST[$inputname])) != 11) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Minimum length, specifiy the input id, the minimum characther size and the error message
	 *
	 * @param string $inputname
	 * @param integer $minimum
	 * @param string $errormessage
	 * @param boolean $optional
	 */
	function minLength($inputname,$minimum,$error_message)
	{
		$this->js.='if (!minLength(formname.'.$inputname.','.$minimum.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && (strlen($_POST[$inputname]) < $minimum) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Maximum length, specifiy the input id, the maximum characther size and the error message
	 *
	 * @param string $inputname
	 * @param integer $maximum
	 * @param string $errormessage
	 * @param boolean $optional
	 */
	function maxLength($inputname,$maximum,$error_message)
	{
		$this->js.='if (!maxLength(formname.'.$inputname.','.$maximum.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && (strlen($_POST[$inputname]) > $maximum) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Not empty, first parameter is the input id, second parameter specifies the message that will be displayed it the input is empty
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function notEmpty($inputname,$error_message)
	{
		$this->js.='if (!isEmpty(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && ( strlen($_POST[$inputname]) <= 0 ))
		{
			if ($error_message != null)
			{
				$this -> error_message .= $error_message.'<br>';
			}
		}
	}
	/**
	 * Not empty, first parameter is the input id, second parameter specifies the message that will be displayed it the input is empty
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function notEmptyGroup()
	{
		$arg_list = func_get_args();
		$error_message = $arg_list[count($arg_list)-1];
		$inputs = '';
		$count = count($arg_list) - 1;
		$counter = 0;
		for ($i = 0; $i < $count; $i++)
		{
			$inputs .= 'formname.'.$arg_list[$i].',';
			if ( isset($_POST[$arg_list[$i]]) && ( strlen($_POST[$arg_list[$i]]) <= 0 ))
			{
				$counter++;
			}
		}
		$this->js.='if (!isEmptyGroup('.$inputs.'"'.$error_message.'")) return false;';
		if ($counter == $count)
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * Limit spaces, first parameter is the input id, second parameter specifies the message that will be displayed it the input is empty
	 *
	 * @param string $inputname
	 * @param integer $limit
	 * @param string $errormessage
	 */
	function limitSpaces($inputname,$limit,$error_message)
	{
		$this->js.='if (!limitSpaces(formname.'.$inputname.','.$limit.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && strlen($_POST[$inputname]) - strlen(str_replace(' ','',$_POST[$inputname])) > $limit && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	/**
	 * The same as not empty, but used for dropdowns and elements which should not be highlighted
	 *
	 * @param string $inputname
	 * @param string $errormessage
	 */
	function selected($inputname,$error_message)
	{
		$this->js.='if (!selected(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && ( strlen($_POST[$inputname]) <= 0 ) || !isset($_POST[$inputname])) // Required for disabled values in select menu
		{
			if ($error_message != null)
			{
				$this -> error_message .= $error_message.'<br>';
			}
		}
	}

	function excludesSpecial($inputname,$error_message)
	{
		$this->js.='if (!excludesSpecial(formname.'.$inputname.',"'.$error_message.'")) return false;';
		if ( isset($_POST[$inputname]) && (!preg_match("/^[a-zA-Z0-9_]*$/", $_POST[$inputname])) && $_POST[$inputname] != "")
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	
	function fieldMatch($inputname,$inputname2,$error_message)
	{
		$this->js.='if (!isMatch(formname.'.$inputname.',formname.'.$inputname2.',"'.$error_message.'")) return false;';
		if ( (isset( $_POST[$inputname] ) || isset( $_POST[$inputname2] ) ) && ( $_POST[$inputname] !== $_POST[$inputname2] ))
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	
	function notMatch($inputname,$inputname2,$error_message)
	{
		$this->js.='if (!notMatch(formname.'.$inputname.',formname.'.$inputname2.',"'.$error_message.'")) return false;';
		if ( (isset( $_POST[$inputname] ) || isset( $_POST[$inputname2] ) ) && ( $_POST[$inputname] === $_POST[$inputname2] ))
		{
			$this -> error_message .= $error_message.'<br>';
		}
	}
	
	function checked($inputname,$error_message)
	{
	/*	if ($_POST && ($_POST[$inputname] == ''))
		{
			$view = view :: getInstance();
			$view -> error_message = $errormessage;
		} else*/
		$this->js.='if (!checked(formname.'.$inputname.',"'.$error_message.'")) return false;';
	}
	
	/**
	 * This functions generates the validation javascript
	 *
	 */
	function valid()
	{
		if ( $this -> error_message == null )
		{
			return true;
		}
		else
		{
			return $this -> error_message;
		}
	}
	
	function parse()
	{
		$this->js.='
			if (errors !== "")
			{
				var select = null;
				
				if (field_count == 0) { field_count = fields.length; }
				var count = 0;
				for (var i = 0; i < fields.length; i++)
				{
					if (fields[i][1] === false && count < field_count)
					{
						//if (select === null && fields[i][2] !== false) { select = fields[i][0]; }
						if (fields[i][2] !== false)
						{
							fields[i][0].style.backgroundColor=error_color;
						}
						else
						{
							fields[i][0].style.backgroundColor="";
						}
						count++;
					}
					else
					{
						fields[i][0].style.backgroundColor="";
					}
				}
				alert(errors);
				//select.focus();
				//select.blur();
				//select.select();
				errors = "";
				fields = [];
				lfield = null;
				select = null;
				counter = 0;
				return false;
			}
			for (var i = 0; i < fields.length; i++)
			{
				fields[i][0].style.backgroundColor="";
			}
		';
		//generate javascript
		//add event handlers via jquery in $validation_js_code, allows optional custom event handlers ($formbutton added to constructor)
		return "
			error_color = '$this->error_color';
			error_count = '$this->error_count';
			field_count = '$this->field_count';
			{$this -> js};
			{$this -> exfunction};
			return true;};
			function {$this->formName}init(e)
			{
				if ( validate$this->formName(document.{$this->formName}) == false || '{$this->stop}' == true )
				{
					if (!e) var e = window.event;
					if (e.preventDefault)
					{
						  e.preventDefault();
						  e.stopPropagation();
					}
					else
					{
						  e.returnValue = false;
						  e.cancelBubble = true;
					}
					return false;
				}
				else
				{
					return true;
				}
				//cancel submit if validate returns false;
			}
		";
		/**
		//generate javascript
		//add event handler for firefox, ie and other browsers, and set input error background
		return "
			error_color = '$this->error_color';
			error_count = '$this->error_count';
			field_count = '$this->field_count';
			{$this -> js};
			{$this -> exfunction};
			return true;};
			function {$this->formName}init(e)
			{
				if ( validate$this->formName(document.{$this->formName}) == false || '{$this->stop}' == true )
				{
					if (!e) var e = window.event;
					if (e.preventDefault)
					{
						  e.preventDefault();
						  e.stopPropagation();
					}
					else
					{
						  e.returnValue = false;
						  e.cancelBubble = true;
					}
					return false;
				}
				else
				{
					return true;
				}
				//cancel submit if validate returns false;
			}
			if (document.getElementById('{$this->formName}').addEventListener)
			{
				document.getElementById('{$this->formName}').addEventListener('submit', {$this->formName}init, false);
			}
			else if (document.getElementById('{$this->formName}').attachEvent)
			{
				document.getElementById('{$this->formName}').attachEvent('onsubmit', {$this->formName}init);
			}
			else
			{
				document.getElementById('{$this->formName}').onclick = {$this->formName}init;
			}
		";
		**/
	}
}
?>