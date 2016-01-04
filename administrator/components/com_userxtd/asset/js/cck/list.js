/**
 * The list operator
 */

;
(function($)
{
	"use strict";

	/**
	 * Windwalker CCK list operator
	 */
	window.WindwalkerCCKList = {

		/**
		 * Init this object.
		 *
		 * @param option
		 */
		init: function(option)
		{
			this.option = option;

			this.$table  = $('#ak-attr-table');
			this.$inputs = this.$table.find('input');
			this.$trs    = this.$table.find('tr');
			this.$tbody  = this.$table.find('tbody')[0];

			this.registerEvents(option);
		},

		/**
		 * Register events.
		 *
		 * @param option
		 */
		registerEvents: function(option)
		{
			var self = this;

			// Set numbers when loaded.
			var num  = self.$trs.length - 2;
			var i    = -1;

			// Set Number to detect add new or not.
			self.$trs.each( function(index, e)
			{


				e = $(e);

				e.attr('num', i);

				e.find('input').attr('num', i);

				i++;
			});

			self.countRow = i;
		},

		/**
		 * Set Attr.
		 *
		 * @param tr
		 * @param i
		 * @returns {*}
		 */
		setAttr: function(tr, i)
		{
			var $tr = $(tr);

			// Set New Element attrs
			$tr.find('input.attr-default').attr({'id': 'option-' + i, 'value': i, 'num': i, checked: false});
			$tr.find('input.attr-value').attr({'name': 'attrs[options][value][]', 'value': '', 'num': i});
			$tr.find('input.attr-text').attr({'name': 'attrs[options][text][]', 'value': '', 'num': i});

			$tr.attr({
				class: 'row' + (i % 2),
				num: i
			});

			return $tr;
		},

		/**
		 * Add attr row
		 *
		 * @param e
		 */
		addAttrRow: function(e)
		{
			var n = parseInt($(e).attr('num'));

			if ((n + 1) != this.countRow)
			{
				return;
			}

			var tr = this.$trs[this.$trs.length - 1].clone();

			tr = this.setAttr(tr, this.countRow);

			this.$table.append(tr);

			this.countRow++;
		},

		/**
		 * Add new option.
		 *
		 * @param e
		 */
		addOption: function(e)
		{
			var tr1 = $(e).parents('tr').first();
			var tr = tr1.clone();
			var n  = parseInt(tr.attr('num'));

			tr = this.setAttr(tr, n);

			tr.insertAfter(tr1);
		},

		/**
		 * Delete option.
		 *
		 * @param e
		 */
		deleteOption: function(e)
		{
			var tr = $(e).parents('tr')[0];

			$(tr).remove();

			this.countRow--;
		},

		/**
		 * Set default
		 *
		 * @param e
		 */
		setDefault: function(e)
		{
			e = $(e);

			var v = e.val();

			e.parents('tr').find('input.attr-default').val(v);
		}
	};
})(jQuery);
