define([
	"dojo/_base/declare",
	"dojo/_base/lang",
	"dojo/on",
	"dojo/dom-construct",
	"dojo/dom-style",
	'dijit/form/SimpleTextarea',
	"dijit/_WidgetBase"
], function (
	declare, 
	lang, 
	on, 
	domConstruct,
	domStyle,
	SimpleTextarea, 
	_WidgetBase
) {
	return declare('counterTextArea', [SimpleTextarea, _WidgetBase], {
		intermediateChanges: true,
		maxCharsAllowed: 100,
		counterNode: null,
		counterNodeTemplate: '<span style="font-size: 11px; clear: both; margin-top: 3px; display: block;" id="textarea-counter"></span>',
		counterHTML: '{usedChars} / {allowedChars}',
		startup: function () {
			this.inherited(arguments);

			if (this.maxLength) {
				this.maxCharsAllowed = parseInt(this.maxLength, 10);
			} else {
				this.maxLength = this.maxCharsAllowed;
			}

			this.counterNode = domConstruct.place(this.counterNodeTemplate, this.domNode, "after");

			dojo.connect(this, 'onChange', lang.hitch(this, this._process));

			this._process();
		},
		_process: function (value) {
			var val = value !== undefined ? value.length : this.value.length;
			this._onInput();
			this._updateCounter(val);
		},
		_updateCounter: function (val) {
			if (!this.counterNode) {
				return;
			}

			this.counterNode.innerHTML = lang.replace(this.counterHTML, {
				usedChars: val,
				allowedChars: this.maxCharsAllowed
			})

			if (val >= this.maxCharsAllowed) {
				domStyle.set(this.counterNode, 'color', 'red');
			} else {
				domStyle.set(this.counterNode, 'color', 'black');
			}
		}
	})
})