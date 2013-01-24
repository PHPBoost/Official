/* #### Constraints #### */
function integerIntervalValidator(value, lbound, rbound)
{
	var prev_value = value;
	var value = parseInt(value);
	if (value != prev_value || value < lbound || value > rbound)
	{
		return false;
	}
	return true;
}
function lengthIntervalValidator(value, lbound, rbound)
{
	var value = value.length;
	if (value < lbound || value > rbound)
	{
		return false;
	}
	return true;
}
function nonEmptyFormFieldValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
    if (field)
    {
            if (field.getValue() == null || field.getValue() == '')
            {
                    return message;
            }
    }
    return '';
}

function regexFormFieldValidator(field_id, regexPattern, options, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			regex = new RegExp(regexPattern, options);
			if (!regex.test(value))
			{
				return message;
			}
		}
	}
	return '';
}

function integerIntervalFormFieldValidator(field_id, lbound, rbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!integerIntervalValidator(value, lbound, rbound))
			{
				return message;
			}
		}
	}
	return '';
}

function lengthFormFieldValidator(field_id, lbound, rbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!lengthIntervalValidator(value, lbound, rbound))
			{
				return message;
			}
		}
	}
	return '';
}

function LoginExistValidator(field_id, message, user_id)
{
    var field = HTMLForms.getField(field_id);
    if (field)
    {
        var value = field.getValue();
        var error = '';
        new Ajax.Request(
            PATH_TO_ROOT + "/kernel/framework/ajax/user_xmlhttprequest.php",
            {
                method: 'post',
                asynchronous: false,
                parameters: {login : value, user_id : user_id, token : TOKEN},
                onSuccess: function(transport) {
                    if (transport.responseText == '1')
                    {
                    	error = message;
                    }
                    else
                    {
                    	error = '';
                    }
                },
            }
        );
        return error;
    }
    return '';
}

function MailExistValidator(field_id, message, user_id)
{
    var field = HTMLForms.getField(field_id);
    if (field)
    {
        var value = field.getValue();
        var error = '';
        new Ajax.Request(
            PATH_TO_ROOT + "/kernel/framework/ajax/user_xmlhttprequest.php",
            {
                method: 'post',
                asynchronous: false,
                parameters: {mail : value, user_id : user_id, token : TOKEN},
                onSuccess: function(transport) {
                    if (transport.responseText == '1')
                    {
                    	error = message;
                    }
                    else
                    {
                    	error = '';
                    }
                },
            }
        );
        return error;
    }
    return '';
}

/* #### Multiple Field Constraints #### */
function equalityFormFieldValidator(field_id, field_id2, message)
{
	var field1 = HTMLForms.getField(field_id);
	var field2 = HTMLForms.getField(field_id2);
	if (field1 && field2) {
		if (field1.getValue() != field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '') {
			return message;
		}
	}
	return "";
}

function inequalityFormFieldValidator(field_id, field_id2, message)
{
	var field1 = HTMLForms.getField(field_id);
	var field2 = HTMLForms.getField(field_id2);
	if (field1 && field2) {
		if (field1.getValue() == field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '') {
			return message;
		}
	}
	return "";
}