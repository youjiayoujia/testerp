var current_url = (window.location+"").split('?');
var current_url = current_url[0].replace('#','');


//删除
function msgdelete(id, urls){
	if(!confirm("是否删除")){
		return false;
	}
	if(!id){
		alert("此条数据不存在,请刷新后在试"); return false;
	}
	var url = urls || (current_url + "/delete");
	
	jQuery.post(url,{id:id},function(data){
		var obj = eval("("+data+")");
		if(obj.status == 1){
			alert(obj.msg);
			window.location.reload();
		}else{
			alert(obj.msg);
		}
	})
}
(function($) {
	
    /**
     * @xbfun插件
     * @author xuebingwang2010@gmail.com
     * 示例:
     * $("#test").xbfun('choosepic',{params1:value,...});
     */
    var xbfun = {
    	//上传图片弹出框,必须依赖layer插件
        choosepic : function(options){
            var defaults = {
        		title:'选择图片',
                callback:function(el,img){
                	console.info(el);
                	console.info(img);
                }
            };

            options = $.extend(defaults, options);
            
            return $(document).on('click',$(this).selector,function(){
            	var o = $(this);
        		var url = o.attr('url');
        		url = url || '/a/upload/image';
        		$.layer({
        		    type   : 2,
        		    btns   : 2, 
        		    shade  : [0.8 , '' , true],
        		    title  : [options.title,true],
        		    iframe : {src : url+"?is_ajax=1"},
        		    area   : ['800px' , '560px'],
        		    success : function(){
                        layer.shift('top', 400);
                        
                    },
                    yes    : function(index){
                    	var img;
                  		layer.getChildFrame('img.img-thumbnail', index).each(function(){
                  			img = $(this);
                        })
                        if(img != null){
                        	options.callback.call(this,o,img);
                        }
                        layer.close(index);
                    }
        		});
            });
        }//上传图片弹出框结束
    };
    
    $.fn.xbfun = function(method) {
        if (xbfun[method]) {
            return xbfun[method].apply(this, Array.prototype.slice.call(arguments,1));
        }  else {
            alert('Method ' +  method + ' does not exist on jQuery.xbfun' );
        }
    };
    
})(jQuery);

//其它操作
$(function(){
	
	//选择框框
	$('table th input:checkbox').on('click' , function(){
		var that = this;
		$(this).closest('table').find('tr > td:first-child input:checkbox')
		.each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		});
			
	});
	
	//批量操作
	jQuery("#hazysubmit").click(function() {

		var hazyid=jQuery("#hazyid").val();
		
		if(hazyid == ''){
			alert('请选择要操作的方式');
			jQuery("#hazyid").focus();
			return false;
		}
		

		var allVals = [];
		
		jQuery('input[name="ids[]"]:checked').each(function() { 
			allVals.push(jQuery(this).val());
		});
		
		if (1 > allVals.length)	{
			alert('请选择您要操作的内容,请在上面的复选框选择');
			return false;
		}
		

		if(!confirm("是否确认操作")){
		   return false;
		}
		
		var url = current_url + "/batch";
		jQuery.post(url,{type:hazyid,id:allVals,field:'status'},function(data){
			var obj=eval("("+data+")");
			if(obj.status == 1) {
				if (window.event) {
					window.event.returnValue = false; //取消默认事件的处理。
				}
				alert('操作成功');
				window.location.reload(); 
			} else {
				alert('操作失败');
			}
		});	
	});
	
	 //状态修改
	$('#tbody_content .ace-switch').bind('click',function(){
		
		var $this = $(this);
    	var id = $this.attr("item_id"); //所在的字段ID,主键
		
    	var val = parseInt($this.val()); //字段值
		
		if(isNaN(val)){ alert("非数，这不是一个数字"); return false;}
		
		//先处理
		
		val = val == 0 ? 1 : 0;
		$this.val(val);
		var url = current_url + "/clicktik";
		
		jQuery.post(url,{id:id,val:val,field:$this.attr('field')},function(data){
			
			var obj = eval("("+data+")");
			
			if(obj.status == 1){

				return false;
			}else{
				
				alert(obj.msg);
				return false;
			}
		})
	
	});


	//排序弹出框
	$(".order_class_id").bind('click',function(){
		var $this = $(this);
		if($this.has('input').length == 0){
			var order_id = $this.text();
			var html = '<div class="input-group"><input type="text" value="'+order_id+'"  class="input-mini spinner-input form-control" ><div class="spinner-buttons input-group-btn"><button class="btn spinner-up btn-xs btn-success" type="button"><i class="icon-ok smaller-75"></i></button></div></div>';
			$this.html(html);
		}
	})
	
	//排序提交框
	$(document).on('click','.order_class_id .btn',function(){
		var $this = $(this);
		var p = $this.parents('.order_class_id');
		
		var val = p.find("input").val();
		var id = p.attr("item_id");
		var field = p.attr("field");
		var url = current_url + "/order_insert";
		
		jQuery.post(url,{val:val,id:id,field:field},function(data){
			var obj = eval("("+data+")");	
			if(obj.status == 1){
				p.html(val);
			}else{
				return false;	
			}
		})
	})
})

var timeoutObject;
//showtips("信息","时长","颜色")lcc ->2013-07-12
function showtips(content, class_name) {
    
    var object = timeoutObject;
    class_name = class_name || 'alert-danger';
    
    if( $('#_showtips').length > 0 ) {
        $('#_showtips').remove();
        clearTimeout(object);
    }
    

//    var string  = '<p id="_showtips" style="color:#FFF;font:12px simsun, arial, verdana;font-weight:bold;height:24px;line-height:24px;';
//        string += '_line-height:26px;overflow:hidden;opacity:0.95;filter:alpha(opacity=95);padding:0 12px;border';
//        string += '-radius:2px;z-index:999;text-shadow:1px 1px 0px #555;box-shadow:1px 1px 0px #555;display:none">' + content + '</p>';

        
    var string  = '<div id="_showtips" class="alert '+class_name+'" style="z-index:999;display:none">';
    	string += content;
        string += '</div>';
        
    $('body').append(string);
    
    $('#_showtips').css({ position:'absolute', top: $(document).scrollTop() + 150, left: $(window).width()/2 - $('#_showtips').width()/2 }).show();
    
    $(window).bind({
        resize: function() {
            if( $('#_showtips').length > 0 ) {
                $('#_showtips').css({ position:'absolute', top: $(document).scrollTop() + 150, left: $(window).width()/2 - $('#_showtips').width()/2 });
            }
        },
        scroll: function() {
            if( $('#_showtips').length > 0 ) {
                $('#_showtips').css({ position:'absolute', top: $(document).scrollTop() + 150, left: $(window).width()/2 - $('#_showtips').width()/2 });
            }
        }
    });
    
    if( $('#_showtips').length > 0 ) {
    	timeoutObject = setTimeout(showtipsclose, 3000);
    }
}

/** showtipsclose */
function showtipsclose() {
    if( $('#_showtips').length > 0 ) {
    	if(timeoutObject != null){
    		clearTimeout(timeoutObject);
    	}
        $('#_showtips').remove();
    }
}



/** showxbtips */
function showxbtips(content, class_name) {
	showtipsclose();
    var o = ($(document).scrollTop() > 0) ? 0 : 4;
    class_name = class_name || 'alert-success';
    
    if( $('#_showxbtips').length > 0 ) {
        $('#_showxbtips').remove();
    }
    
    var string  = '<div id="_showxbtips" class="alert '+class_name+'" style="z-index:999;display:none">';
    	string += content;
    	string += '<button id="closebutton" class="close" type="button">×</button>';
        string += '</div>';
    
    var dialogbg = "<div id='_showxbbg' style='width:" + $(window).width() + "px;height:" + ($(document).height() - o) + "px;border:none;background:#000;position:absolute;top:0px;left:0px;z-index:998;opacity:0.3;filter:alpha(opacity=30);display:none'></div>";
    $('body').append(dialogbg);
    $('#_showxbbg').fadeIn(100);

    $('body').append(string);
    
    $('#_showxbtips').css({ position:'absolute', top: ($(window).height()/2 - $('#_showxbtips').height()/2) + $(document).scrollTop() - 10, left: ($(window).width()/2 - $('#_showxbtips').width()/2) - 8, display: 'block' });

    $('#closebutton').bind({
        click: function() {
        	showxbtipsclose(2);
        }
    });
    $('#_showxbbg').bind({
        click: function() {
        	showxbtipsclose(2);
        }
    });
    
    $(window).bind({
        resize: function() {
            if( $('#_showxbtips').length > 0 ) {
                $('#_showxbbg').css({ width: $(window).width(), height: $(document).height() });
                $('#_showxbtips').css({ position:'absolute', top: ($(window).height()/2 - $('#_showxbtips').height()/2) + $(document).scrollTop() - 10, left: ($(window).width()/2 - $('#_showxbtips').width()/2) - 8, display: 'block' });
            }
        },
        scroll: function() {
            if( $('#_showxbtips').length > 0 ) {
                $('#_showxbbg').css({ width: $(window).width(), height: $(document).height() });
                $('#_showxbtips').css({ position:'absolute', top: ($(window).height()/2 - $('#_showxbtips').height()/2) + $(document).scrollTop() - 10, left: ($(window).width()/2 - $('#_showxbtips').width()/2) - 8, display: 'block' });
            }
        }
    });

    if( $('#_showxbtips').length > 0 ) {
    	timeoutObject = setTimeout(showxbtipsclose,800);
    }
}


/** showxbtipsclose */
function showxbtipsclose(time) {
    var t = (time == null) ? 2 : parseInt(time);
    if( $('#_showxbtips').length > 0 ) {
        $('#_showxbbg, #_showxbtips').fadeOut( t * 100, function() {
            $(this).remove();
        });
    }
}

//复制行函数
function copyRow(id, url) {
	if (!confirm('确定要复制吗？')) {
		return false;
	}

	if (!id) {
		showtips('数据不存在', 'alert-warning');
		return false;
	}

	$.ajax({
		url: url,
		data: 'id=' + id,
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			if (data.status) {
				showtips(data.info);
				window.location.reload();
			} else {
				showtips(data.info, 'alert-warning');
			}
		}
	});
}

/**
 * 打开新窗口函数
 * @param windowName:窗口名称
 */
function openNewSpecifiedWindow( windowName )
{
	window.open('',windowName,'width=700,height=400,menubar=no,scrollbars=no');
}