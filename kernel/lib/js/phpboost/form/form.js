// This contains all the HTML forms contained in the page
var HTMLForms = Class.create();
HTMLForms.forms = new Array();
HTMLForms.add = function(form) {
	return HTMLForms.forms.push(form);
};
HTMLForms.get = function(id) {
	var form = null;
	HTMLForms.forms.each(function(aForm) {
		if (aForm.getId() == id) {
			form = aForm;
			throw $break;
		}
	});
	return form;
};
HTMLForms.has = function(id) {
	return HTMLForms.get(id) != null;
};
HTMLForms.getFieldset = function(id) {
	var fieldset = null;
	HTMLForms.forms.each(function(form) {
		var aFieldset = form.getFieldset(id);
		if (aFieldset != null) {
			fieldset = aFieldset;
			throw $break;
		}
	});
	return fieldset;
};
HTMLForms.getField = function(id) {
	var field = null;
	HTMLForms.forms.each(function(form) {
		var aField = form.getField(id);
		if (aField != null) {
			field = aField;
			throw $break;
		}
	});
	return field;
};

// Shortcuts
var $HF = HTMLForms.get;
var $FFS = HTMLForms.getFieldset;
var $FF = HTMLForms.getField;

// This represents a HTML form.
var HTMLForm = Class.create({
	fieldsets : new Array(),
	id : "",
	initialize : function(id) {
		this.id = id;
		this.fieldsets = new Array();
	},
	getId : function() {
		return this.id;
	},
	addFieldset : function(fieldset) {
		this.fieldsets.push(fieldset);
		fieldset.setFormId(this.id);
	},
	getFieldset : function(id) {
		var fieldset = null;
		this.fieldsets.each(function(aFieldset) {
			if (aFieldset.getId() == id) {
				fieldset = aFieldset;
				throw $break;
			}
		});
		return fieldset;
	},
	getFieldsets : function() {
		return this.fieldsets;
	},
	hasFieldset : function(id) {
		var hasFieldset = false;
		this.fieldsets.each(function(aFieldset) {
			if (aFieldset.getId() == id) {
				hasFieldset = true;
				throw $break;
			}
		});
		return hasFieldset;
	},
	getFields : function() {
		var fields = new Array();
		this.fieldsets.each(function(fieldset) {
			fieldset.getFields().each(function(field) {
				fields.push(field);
			});
		});
		return fields;
	},
	getField : function(id) {
		var field = null;
		this.getFields().each(function(aField) {
			if (aField.getId() == id) {
				field = aField;
				throw $break;
			}
		});
		return field;
	},
	validate : function() {
		var validated = true;
		var form = this;
		this.getFields().each(function(field) {
			var validation = field.validate();
			if (validation != "") {
				form.displayValidationError(validation);
				validated = false;
				throw $break;
			}
		});
		this.registerDisabledFields();
		return validated;
	},
	displayValidationError : function(message) {
		message = message.replace(/&quot;/g, '"');
		message = message.replace(/&amp;/g, '&');
		alert(message);
	},
	registerDisabledFields : function() {
		var disabledFields = "";
		this.getFields().each(function(field) {
			if (field.isDisabled()) {
				disabledFields += "|" + field.getId();
			}
		});
		$(this.id + '_disabled_fields').value = disabledFields;

		var disabledFieldsets = "";
		this.getFieldsets().each(function(fieldset) {
			if (fieldset.isDisabled()) {
				disabledFieldsets += "|" + fieldset.getId();
			}
		});
		$(this.id + '_disabled_fieldsets').value = disabledFieldsets;
	}
});

// This represents a fieldset
var FormFieldset = Class.create({
	fields : new Array(),
	id : "",
	disabled : false,
	formId : "",
	initialize : function(id) {
		this.id = id;
		this.fields = new Array();
		this.disabled = false;
	},
	getId : function() {
		return this.id;
	},
	getHTMLId : function() {
		return this.formId + '_' + this.id;
	},
	setFormId : function(formId) {
		this.formId = formId;
	},
	addField : function(field) {
		this.fields.push(field);
		field.setFormId(this.formId);
	},
	getField : function(id) {
		var field = null;
		this.fields.each(function(aField) {
			if (aField.getId() == id) {
				field = aField;
				throw $break;
			}
		});
		return field;
	},
	getFields : function() {
		return this.fields;
	},
	hasField : function(id) {
		var hasField = false;
		this.fields.each(function(field) {
			if (field.getId() == id) {
				hasField = true;
				throw $break;
			}
		});
		return hasField;
	},
	enable : function() {
		this.disabled = false;
		Effect.Appear(this.getHTMLId());
		this.fields.each(function(field) {
			field.enable();
		});
	},
	disable : function() {
		this.disabled = true;
		Effect.Fade(this.getHTMLId());
		this.fields.each(function(field) {
			field.disable();
		});
	},
	isDisabled : function() {
		return this.disabled;
	}
});

// This represents a field. It can be overloaded to fit to different fields
// types
var FormField = Class.create({
	id : 0,
	validationMessageEnabled : false,
	formId : "",
	initialize : function(id) {
		this.id = id;
	},
	getId : function() {
		return this.id;
	},
	getHTMLId : function() {
		return this.formId + "_" + this.id;
	},
	setFormId : function(formId) {
		this.formId = formId;
	},
	HTMLFieldExists : function() {
		return $(this.getHTMLId()) != null;
	},
	enable : function() {
		if (this.HTMLFieldExists()) {
			Field.enable(this.getHTMLId());
		}
		Effect.Appear(this.getHTMLId() + "_field");
		this.liveValidate();
	},
	disable : function() {
		if (this.HTMLFieldExists()) {
			Field.disable(this.getHTMLId());
		}
		Effect.Fade(this.getHTMLId() + "_field");
		this.clearErrorMessage();
	},
	isDisabled : function() {
		if (this.HTMLFieldExists()) {
			var element = $(this.getHTMLId());
			return element.disabled != "disabled" && element.disabled != false;
		} else {
			var display = $(this.getHTMLId()).style;
			if (display != null) {
				return display == "none";
			} else {
				return false;
			}
		}
	},
	getValue : function() {
		return $F(this.getHTMLId());
	},
	setValue : function(value) {
		Form.Element.setValue($(this.getHTMLId()), value);
	},
	enableValidationMessage : function() {
		this.validationMessageEnabled = true;
	},
	displayErrorMessage : function(message) {
		if (!this.validationMessageEnabled) {
			return;
		}
		if ($('onblurContainerResponse' + this.getHTMLId())
				&& $('onblurMesssageResponse' + this.getHTMLId())) {
			$('onblurContainerResponse' + this.getHTMLId()).innerHTML = '<img src="'
					+ PATH_TO_ROOT
					+ '/templates/'
					+ THEME
					+ '/images/forbidden_mini.png" alt="" class="valign_middle" />';
			$('onblurMesssageResponse' + this.getHTMLId()).innerHTML = message;

			Effect.Appear('onblurContainerResponse' + this.getHTMLId(),
			{
				duration : 0.5
			});
			Effect.Appear('onblurMesssageResponse' + this.getHTMLId(),
			{
				duration : 0.5
			});
		}
	},
	displaySuccessMessage : function() {
		if (!this.validationMessageEnabled) {
			return;
		}
		if ($('onblurContainerResponse' + this.getHTMLId())) {
			$('onblurContainerResponse' + this.getHTMLId()).innerHTML = '<img src="'
					+ PATH_TO_ROOT
					+ '/templates/'
					+ THEME
					+ '/images/processed_mini.png" alt="" class="valign_middle" />';
			Effect.Appear('onblurContainerResponse' + this.getHTMLId(),
			{
				duration : 0.2
			});

			Effect.Fade('onblurMesssageResponse' + this.getHTMLId(), {
				duration : 0.2
			});
		}
	},
	clearErrorMessage : function() {
		if ($('onblurContainerResponse' + this.getHTMLId())) {
			$('onblurContainerResponse' + this.getHTMLId()).innerHTML = '';

			Effect.Appear('onblurContainerResponse' + this.getHTMLId(),
			{
				duration : 0.2
			});

			Effect.Fade('onblurMesssageResponse' + this.getHTMLId(), {
				duration : 0.2
			});
		}
	},
	liveValidate : function() {
		if (!this.isDisabled()) {
			var errorMessage = this.doValidate();
			if (errorMessage != "") {
				this.displayErrorMessage(errorMessage);
			} else {
				this.displaySuccessMessage();
			}
		}
	},
	validate : function() {
		if (!this.isDisabled()) {
			return this.doValidate();
		}
		return "";
	},
	doValidate : function() {
		return '';
	}
});