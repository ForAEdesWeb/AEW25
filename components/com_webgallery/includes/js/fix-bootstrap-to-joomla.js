/* Fix Bluestork and Bootstrap Conflict */
window.addEvent( 'domready', function(){
    // toolbar
    var div = $$('#toolbar')[0] ;
    var ul = $$('#toolbar ul');
    var li = $$('#toolbar li');
    var span = $$('.toolbar-list .button span');
    var a = $$('.toolbar-list .button a');
    
    div.addClass('btn-toolbar');
    ul.addClass('');
    span.set('class', '');
    
    $$('li.divider').hide();
    li.addClass('btn');
    li.removeClass('button');
    
    $$('#toolbar-apply', '#toolbar-new').addClass('btn-info');
    $$('#toolbar-save', '#toolbar-edit').addClass('btn-primary');
    $$('#toolbar-cancel', '#toolbar-trash').addClass('btn-danger');
    
    // model button
    $$('div.button2-left a').addClass('btn');
    
    // access
    $$('.group-rules td span').set('class', '');
} );