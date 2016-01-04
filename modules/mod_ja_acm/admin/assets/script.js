(function ($) {
    $.fn.bindActions = function (btn, object) {
        var $element = $(this);
        $element.find(btn).on('click', function () {
            var action = $(this).data('action'),
                func = object[action] ? action : action.replace('-', '_');
            if (typeof object[func] == 'function') {
                object[func](this);
                setTimeout(function () {
                    $element.trigger(action)
                }, 100);
            }
        })
    };

    $.fn.elasticTextarea = function (events) {
        var $element = $(this);
        var autoheightResize = function (box, reset) {
            var $box = $(box),
                padding = $box.outerHeight() - $box.height();
            if (reset) {
                $box.height(20);
            } else if ($box.data('scrollheight') == box.scrollHeight) {
                // not reset & scrollheight not change
                return;
            }
            // update height
            $box.height(box.scrollHeight - padding);
            // store current scroll height
            $box.data('scrollheight', box.scrollHeight);
        };

        $element.find('textarea').addClass('autoheight').on("keyup change", function () {
            autoheightResize(this);
        }).on("focus blur resize update", function () {
            autoheightResize(this, true);
        });

        $element.on(events, function () {
            $element.find('textarea').trigger('update');
        })
    };

    $.fn.jaclone = function (idx) {
        var $item = $(this),
            $newitem = $item.clone(true, true),
            atags = $newitem.find('a');

        $newitem.find('input, select, textarea').each(function () {
            // update id, name
            var newid = this.id + '_' + idx,
                oldid = this.id;
            // update label for
            $newitem.find('[for="' + oldid + '"]').attr('for', newid).attr('id', newid + '-lbl');
            this.id = newid;
            this.name = $(this).data('name') + '[' + idx + ']';

            // find a tag and update id
            atags.each(function (i, a) {
                if (a.href) a.href = a.href.replace('fieldid=' + oldid + '&', 'fieldid=' + newid + '&');
                if ($(a).attr('onclick')) $(a).attr('onclick', $(a).attr('onclick').replace('\'' + oldid + '\'', '\'' + newid + '\''));
            });

            // update image preview tips
            var regex = new RegExp('"' + oldid + '_preview', 'gm'),
                oldtips = $item.find('.hasTipPreview'),
                newtips = $newitem.find('.hasTipPreview');
            oldtips.each(function (i, tip) {
                if (tip.retrieve && tip.retrieve('tip:title') && tip.retrieve('tip:text') && tip.retrieve('tip:text').match(regex)) {
                    newtips[i].store('tip:title', tip.retrieve('tip:title'));
                    newtips[i].store('tip:text', tip.retrieve('tip:text').replace(regex, '"' + newid + '_preview'));
                } else if (tip.title.match(regex)) {
                    newtips[i].title = tip.title.replace(new RegExp('"' + oldid + '_preview', 'gm'), '"' + newid + '_preview');
                }
            });

            // update button
            var $button = $newitem.find('#' + oldid + '_img');
            if ($button.length) {
                $button.attr('id', newid + '_img');
            }
        });

        return $newitem;
    };

})(jQuery);


var jaTools = {};
(function ($) {
    jaTools.fixCloneObject = function ($newitem, bindEvents) {
        // fix for jQuery Chosen
        if ($newitem.find('select').hasClass('chzn-done')) {
            // remove chosen if found and recreate it
            $newitem.find('.chzn-container').remove();
            $newitem.find('select').data('chosen', null).chosen();
        }

        // rebind events for image button & tips
        if (bindEvents) {
            // enable modal
            SqueezeBox.assign($newitem.find('a').filter('.modal').get(), {
                parse: 'rel'
            });
            // init new tips
            new Tips($newitem.find('.hasTip').get(), {maxTitleChars: 50, fixed: false});
            new Tips($newitem.find('.hasTipPreview').get(), {
                maxTitleChars: 50,
                fixed: false,
                onShow: jMediaRefreshPreviewTip
            });
        }
    };

    jaTools.getVal = function (elem, $parent) {
        var $elem = $(elem),
            name = $elem.data('name'),
            type = $elem.attr('type'),
            $fields = $parent.find($elem.prop('tagName')).filter(function () {
                return $(this).data("name") == name
            });

        if (type == 'checkbox') {

        }
        if (type == 'radio') $fields = $fields.filter(':checked');

        return $fields.map(function () {
            return type == 'checkbox' ? $(this).prop('checked') : $(this).val()
        }).get();
    };

    jaTools.setVal = function ($elem, value) {
        if (!$elem.length) return;
        var type = $elem.attr('type'),
            tag = $elem.prop('tagName');
        if (type == 'radio') {
            $elem.removeAttr('checked').filter('[value="' + value + '"]').prop('checked', true);
        } else if (type == 'checkbox') {
            $elem.prop('checked', value);
        } else if (tag == 'TEXTAREA') {
            $elem.val(value);
        } else if (tag == 'SELECT') {
            $elem.val(value);
            if ($elem.val() != value) {
                $elem.val($elem.find('option:first').val());
            }
        } else {
            $elem.val(value);
        }
    };
})(jQuery);

jaToolsInit = function ($) {

    var $allElems, activeType, $activeType, advancedForm, configs = null;


    var initConfigForm = function (bindEvent) {
        // Update config data
        if (!configs) {
            configs = getJSon(decodeHtml($('#jatools-config').val()));
        }
        // update value for layout param
        if (configs[':type'] || configs[':layout']) {
            var $types = $('#jatools-type');
            $types.val(configs[':type'] ? configs[':type'] : configs[':layout']);
            if ($types.val() == null) {
                $types.val('');
            }
        }
        // display correct form
        displayConfig(bindEvent);
    };

    var displayConfig = function (rebind) {
        var tmp = $('#jatools-type').val().split(':');
        activeType = tmp.length == 1 ? tmp[0].trim() : tmp[1].trim();
        $activeType = $('#jatools-' + activeType);
        $('.jatools-layout-config').addClass('hide');
        $activeType.removeClass('hide');

        // update active form
        updateActiveForm(rebind);

        // remove all required attribute
        $allElems.removeClass('required');
        $allElems.prop('disabled', true);

        // add required for selected layout only
        $activeType.find('input, textarea, select').prop('disabled', false).each(function () {
            if ($(this).data('required')) $(this).addClass('required');
        });

        switchLayout();
        // fix runtime elements
        fixSwitchType();
    };

    var updateVal = function (fname) {
        var $elem = $allElems.filter(function () {
            return $(this).data("name") == fname
        }).first();
        if ($elem.data('ignoresave')) return;
        // check if this fields in a hide group - used for other style
        if ($elem.parents('.control-group').first().hasClass('hide')) return;
        if ($elem.parents('.jatools-group').first().hasClass('hide')) return;

        var val = $elem.data('acm-object') ? $elem.data('acm-object').getData() : jaTools.getVal($elem, $activeType),
            layout = $elem.parents('.jatools-layout-config'),
            layout_name = layout.attr('id').substr('jatools-'.length);
        if (!configs[layout_name]) configs[layout_name] = {};
        configs[layout_name][fname] = val;
    };

    var getJSon = function (str) {
        var result = {};
        try {
            result = JSON.parse(str.trim());
        } catch (e) {
            return {};
        }
        return $.isPlainObject(result) ? result : {};
    };

    var encodeHtml = function (str) {
        return String(str)
            .replace(/</g, '((')
            .replace(/>/g, '))');
    };

    var decodeHtml = function (str) {
        return String(str)
            .replace(/\(\(/g, '<')
            .replace(/\)\)/g, '>');
    };

    var switchLayout = function () {
        var layout = $activeType.find('.jatools-layouts').val();
        // unhide and add required if needed
        $activeType
            .find('.control-group, .jatools-group')
            .removeClass('hide')
            .find('input, select, textarea')
            .prop('disabled', false)
            .filter(function () {
            return $(this).data('required')
        })
            .addClass('required');

        // hide, disable and remove required elements not used for selected layout
        $activeType
            .find('.control-group, .jatools-group')
            .filter(function () {
                var layouts = $(this).data('layouts');
                return layouts && !layouts.match(new RegExp('(^|,)\\s*' + layout + '\\s*(,|$)', 'i'));
            })
            .addClass('hide')
            .find('input, select, textarea')
            .prop('disabled', true)
            .removeClass('required');
    };

    var updateActiveForm = function (rebind) {
        // update value to form
        if (!activeType || !$activeType) return;
        var data = configs[activeType] ? configs[activeType] : getJSon(decodeHtml($activeType.find('[name="jatools-sample-data"]').val()));
        if (!data) return;

        $.each(data, function (field_name, value) {
            var $elem = $activeType.find('[name="' + field_name + '"]'),
                field_data = data[field_name],
                group = $elem.parents('.jatools-group');
            if (!$elem.length) return;

            if ($elem.data('acm-object')) {
                $elem.data('acm-object').bindData(field_name, data);
            } else if ($.isArray(field_data) && group.hasClass('jatools-multiple')) {
                // find this field
                var $rows = group.find('.jatools-row');
                if ($rows.length && field_data.length > $rows.length) {
                    var $lastrow = $rows.last();
                    for (var i = $rows.length; i < field_data.length; i++) {
                        // clone row
                        $lastrow = $rows.first().jaclone(i).insertAfter($lastrow);
                        jaTools.fixCloneObject($lastrow, rebind);
                    }
                }
                $rows = group.find('.jatools-row');
                // check & update data
                // rows = group.find  ('.jatools-row');
                field_data.each(function (val, i) {
                    var $elem = $($rows[i]).find('input, select, textarea').filter(function () {
                        return $(this).data('name') == field_name
                    });
                    jaTools.setVal($elem, val);
                });
            } else {
                jaTools.setVal($elem, $.isArray(field_data) ? field_data[0] : field_data);
            }
        });

        // compatible with old version
        $activeType.find('.acm-object').each(function () {
            var $this = $(this),
                field_name = this.name;
            if (!data[field_name]) {
                $this.data('acm-object').bindData(field_name, data);
            }
        });
        // end compatible

        // get all form elements
        $allElems = $('.jatools-layout-config').find('input, select, textarea');

        $activeType.trigger('change');
    };

    var fixSwitchType = function () {
        // update chosen for active status
        $('#jatools-type').trigger('liszt:updated');
        $activeType.find('select').trigger('liszt:updated');
    };

    var getData = function () {
        configs = {};
        configs[':type'] = $('#jatools-type').val();
        var $elems = $activeType.find('input, select, textarea'),
            names = $elems.map(function () {
                return $(this).data('name')
            }).get();
        names.each(function (fname, i) {
            updateVal(fname);
        });

        return encodeHtml(JSON.stringify(configs));
    };

    var updateData = function () {
        // close dialog
        advancedForm.dialog("close");

        // update data to form
        var _config = getJSon(decodeHtml($("#acm-advanced-input").val()));

        if (_config[':type']) {
            configs = _config;
            initConfigForm(true);
        }
    };

    var advancedAction = function () {
        var $button = $('#toolbar-advanced').children();
        if (!$button.length) return;
        $button.attr('onclick', '');
        $button.on('click', function () {
            $("#acm-advanced-input").val(getData());
            advancedForm.dialog("open");
            return false;
        })
    };

    var advancedFormInit = function () {
        // init dialog
        advancedForm = $("#acm-advanced-form").dialog({
            autoOpen: false,
            height: 400,
            width: 550,
            modal: true,
            buttons: {
                "Update": updateData,
                Cancel: function () {
                    advancedForm.dialog("close");
                }
            }
        });

        // bind action button
        advancedAction();
    };

    var confirm = function (actions) {
        var confirmbox = $('#acm-dialog-confirm');
        if (!confirmbox.length) {
            confirmbox = $('<div id="acm-dialog-confirm" title="Confirm">Are you ok ?</div>').appendTo('body');
        }
        confirmbox.dialog({
            resizable: false,
            modal: true,
            height: 250,
            width: 400,
            buttons: {
                "Yes": function () {
                    $(this).dialog('close');
                    actions['yes']();
                },
                "No": function () {
                    $(this).dialog('close');
                    if (actions['no']) actions['no']();
                }
            }
        });
    };

    // get all form elements
    $allElems = $('.jatools-layout-config').find('input, select, textarea');

    $allElems.each(function () {
        var $this = $(this);
        if ($this.hasClass('required')) {
            $this.data('required', 1).removeClass('required');
        }
        $this.data('name', this.name);
    });

    initConfigForm(false);

    // switch config when change type
    $('#jatools-type').on('change', function () {
        displayConfig(true);
    });

    // show/hide fields when change layout
    $('.jatools-layouts').on('change', function () {
        switchLayout();
    });

    // bind submit event for form
    document.adminForm.onsubmit = function () {
        $('#jatools-config').val(getData());
    };

    // bind event for btn-add, btn-del
    $('.jatools-btn-add').on('click', function () {
        var $rows = $(this).parents('.jatools-group').find('.jatools-row');
        jaTools.fixCloneObject($rows.first().jaclone($rows.length).insertAfter($rows.last()), true);
        // update $allElems
        $allElems = $('.jatools-layout-config').find('input, select, textarea');
    });

    $('.jatools-btn-del').on('click', function () {
        var $this = $(this),
            $row = $this.parent(),
            $fieldset = $row.parent();
        confirm({
            'yes': function () {
                // move this button out
                $this.appendTo($fieldset);
                $row.remove();
                // update $allElems
                $allElems = $('.jatools-layout-config').find('input, select, textarea');
            }
        })
    });

    // hover event for row
    $('.jatools-row').on('mouseenter', function () {
        if ($(this).is($(this).parent().find('.jatools-row').first())) return;
        // check if this is the last row, do nothing
        if ($(this).parent().find('.jatools-row').length < 2) return;
        $(this).parent().find('.jatools-btn-del').appendTo($(this));
    }).on('mouseleave', function () {
        $(this).find('.jatools-btn-del').appendTo($(this).parent());
    });

    // build done, fire change events
    $(document).ready(function () {
        $('.jatools-layout-config').trigger('change');
    });

    // store this
    $.data(document, 'jaToolsACM', this);

    // bind the advanced button
    advancedFormInit();

    // add expand panel button
    var $fsbtn = $('<div class="toggle-fullscreen"><i class="fa fa-expand"></i></div>'),
        $acmadmin = $('.ja-acm-admin');
    if ($acmadmin.hasClass('joomla2')) {
        var $parent = $('body');
        $parent.find('.panel').first().addClass('acm-panel');
        $fsbtn.appendTo('#basic-options').click(function (e) {
            e.stopPropagation();
            if ($parent.hasClass ('full-screen')) {
                $parent.removeClass('full-screen');
            } else {
                $parent.addClass('full-screen');
            }
        });
    } else {
        $fsbtn.appendTo('#general > .row-fluid > .span9 > h3').click(function (e) {
            e.stopPropagation();
            var $panel = $('body');
            if ($panel.hasClass ('full-screen')) {
                $panel.removeClass('full-screen');
            } else {
                $panel.addClass('full-screen');
            }
        });
    }
};
