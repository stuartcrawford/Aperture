var errors = "";
var fields = [];
var lfield = null;
var counter = 0;

function isNumeric(sText,errormessage)
{
	var IsNumber=true;
	var input = parseFloat(sText.value,10);
	if (sText.value.search(/^-?(\d)*\.?(\d)+$/) == -1 && sText.value !== "")
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,false]; } else { fields[fields.length-1] = [sText,false]; }
		lfield = sText;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return IsNumber;
	}
	else
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,true]; }
		lfield = sText;
		return IsNumber;
	}
}

function isRounded(sText,dp,errormessage) // dp must be an integer of 0 or greater
{
	var IsNumber=true;
	var input = parseFloat(sText.value,10);
	if (dp !== "" && Math.round(input*Math.pow(10,dp))/Math.pow(10,dp) != input && sText.value !== "")
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,false]; } else { fields[fields.length-1] = [sText,false]; }
		lfield = sText;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return IsNumber;
	}
	else
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,true]; }
		lfield = sText;
		return IsNumber;
	}
}

function inRange(sText,min,max,errormessage1,errormessage2) // min and max may be null, errormessage1 and errormessage2 may be null if the corresponding parameter is set to null
{
	var IsNumber=true;
	var input = parseFloat(sText.value,10);
	if (min !== "" && input < min && sText.value !== "")
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,false]; } else { fields[fields.length-1] = [sText,false]; }
		lfield = sText;
		if (counter < error_count || error_count == 0) { errors += errormessage1+'\n'; } counter++;
		return IsNumber;
	}
	else if (max !== "" && input > max && sText.value !== "")
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,false]; } else { fields[fields.length-1] = [sText,false]; }
		lfield = sText;
		if (counter < error_count || error_count == 0) { errors += errormessage2+'\n'; } counter++;
		return IsNumber;
	}
	else
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,true]; }
		lfield = sText;
		return IsNumber;
	}
}

function inPounds(field,errormessage)
{
	if (field.value.search(/^\d+(\.\d{2})?$/) != -1 || field.value === "")
	{
		if (lfield != field || lfield === null) { fields[fields.length] = [field,true]; }
		lfield = field;
		return true;
	}
	else
	{
		if (lfield != field || lfield === null) { fields[fields.length] = [field,false]; } else { fields[fields.length-1] = [field,false]; }
		lfield = field;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
}

function isEmail(field,errormessage)
{
	if (field.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1 || field.value === "")
	{
		if (lfield != field || lfield === null) { fields[fields.length] = [field,true]; }
		lfield = field;
		return true;
	}
	else
	{
		if (lfield != field || lfield === null) { fields[fields.length] = [field,false]; } else { fields[fields.length-1] = [field,false]; }
		lfield = field;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
}

function isTelephone(sText,errormessage)
{
	var IsNumber=true;
	var input = parseFloat(sText.value,10);
	if ((sText.value.replace(/ /g,"").search(/^-?(\d)*\.?(\d)+$/) == -1 || sText.value.replace(/ /g,"").length != 11) && sText.value !== "")
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,false]; } else { fields[fields.length-1] = [sText,false]; }
		lfield = sText;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return IsNumber;
	}
	else
	{
		if (lfield != sText || lfield === null) { fields[fields.length] = [sText,true]; }
		lfield = sText;
		return IsNumber;
	}
}

function minLength(aTextField,minlength,errormessage)
{
	if (aTextField.value.length<minlength && aTextField.value !== "")
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,false]; } else { fields[fields.length-1] = [aTextField,false]; }
		lfield = aTextField;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
	else
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,true]; }
		lfield = aTextField;
		return true; }
}

function maxLength(aTextField,maxlength,errormessage)
{
	if (aTextField.value.length>maxlength && aTextField.value !== "")
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,false]; } else { fields[fields.length-1] = [aTextField,false]; }
		lfield = aTextField;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
	else
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,true]; }
		lfield = aTextField;
		return true; }
}

function isEmpty(aTextField,errormessage) //null may be used for errormessage to only change the colour of matching fields
{
	if (aTextField.value.length<=0)
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,false]; } else { fields[fields.length-1] = [aTextField,false]; }
		lfield = aTextField;
		if (errormessage !== "")
		{
			if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		}
		return true;
	}
	else
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,true]; }
		lfield = aTextField;
		return true;
	}
}

function isEmptyGroup()
{
	var errormessage = arguments[arguments.length - 1];
	var count = arguments.length - 1;
	var c = 0;
	for (i = 0; i < count; i++)
	{
		if (arguments[i].value.length<=0)
		{
			c++;
		}
	}
	if (c == count)
	{
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
	}
	return true;
}

function limitSpaces(aTextField,limit,errormessage)
{
	if (aTextField.value.length - aTextField.value.replace(/ /g,"").length > limit && aTextField.value !== "")
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,false]; } else { fields[fields.length-1] = [aTextField,false]; }
		lfield = aTextField;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
	else
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,true]; }
		lfield = aTextField;
		return true;
	}
}

function selected(aTextField,errormessage)
{
	if (aTextField.value.length<=0)
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,false,false]; } else { fields[fields.length-1] = [aTextField,false,false]; }
		lfield = aTextField;
		if (errormessage !== "")
		{
			if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		}
		return true;
	}
	else
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,true]; }
		lfield = aTextField;
		return true;
	}
}

function excludesSpecial(field,errormessage)
{
	if (field.value.search(/^[a-zA-Z0-9_]*$/) != -1 || field.value === "")
	{
		if (lfield != field || lfield === null) { fields[fields.length] = [field,true]; }
		lfield = field;
		return true;
	}
	else
	{
		if (lfield != field || lfield === null) { fields[fields.length] = [field,false]; } else { fields[fields.length-1] = [field,false]; }
		lfield = field;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
}

function isMatch(input1,input2,errormessage)
{
	if (input1.value!=input2.value)
	{
		if (lfield != input2 || lfield === null) { fields[fields.length] = [input1,false]; fields[fields.length] = [input2,false]; } else { fields[fields.length-1] = [input1,false]; fields[fields.length-1] = [input2,false]; }
		lfield = input2;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
	else
	{
		if (lfield != input2 || lfield === null) { fields[fields.length] = [input1,true]; fields[fields.length] = [input2,true]; }
		lfield = input2;
		return true;
	}
}

function notMatch(input1,input2,errormessage)
{
	if (input1.value==input2.value)
	{
		if (lfield != input2 || lfield === null) { fields[fields.length] = [input1,false]; fields[fields.length] = [input2,false]; } else { fields[fields.length-1] = [input1,false]; fields[fields.length-1] = [input2,false]; }
		lfield = input2;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
	else
	{
		if (lfield != input2 || lfield === null) { fields[fields.length] = [input1,true]; fields[fields.length] = [input2,true]; }
		lfield = input2;
		return true;
	}
}

function checked(aTextField,errormessage)
{
	if (aTextField.checked !== true)
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,false,false]; } else { fields[fields.length-1] = [aTextField,false,false]; }
		lfield = aTextField;
		if (counter < error_count || error_count == 0) { errors += errormessage+'\n'; } counter++;
		return true;
	}
	else
	{
		if (lfield != aTextField || lfield === null) { fields[fields.length] = [aTextField,true]; }
		lfield = aTextField;
		return true;
	}
}