/**
 * Created by Lin on 2015/10/14 0014.
 */
/**
 * 模仿android里面的Toast效果，主要是用于在不打断程序正常执行的情况下显示提示数据
 * @param msg
 * @param opt
 * @param left
 * @param top
 */
function warn(msg,opt,left,top){
    if(opt){
        var obj = $("#"+opt);
    }
    new Toast({context:$("body"),message:msg},obj,left,top).show();

}
var Toast = function(config,obj,left,top){
    this.context = config.context==null?$('body'):config.context;//上下文
    this.message = config.message;//显示内容
    this.time = config.time==null?5000:config.time;//持续时间
    this.left = config.left;//距容器左边的距离
    this.top = ($(document).height()/4)*3;//距容器上方的距离
    if(obj){
        this.left = obj.offset().left + left;
        this.top = obj.offset().top + top;
    }
    this.init();
}
var msgEntity;
Toast.prototype = {
    //初始化显示的位置内容等
    init : function(){
        $("#toastMessage").remove();
        //设置消息体
        var msgDIV = [];
        msgDIV.push('<div id="toastMessage">');
        msgDIV.push('<span>'+this.message+'</span>');
        msgDIV.push('</div>');
        msgEntity = $(msgDIV.join('')).appendTo(this.context);
        //设置消息样式
        var left = this.left == null ? window.innerWidth/2 : this.left;
        var top = this.top == null ? '20px' : this.top;
        msgEntity.css({position:'absolute',top:top,'z-index':'99',left:left,'background-color':'black',color:'white','font-size':'12px',padding:'5px',margin:'5px','border-radius':'4px','-moz-border-radius':'4px','-webkit-border-radius':'4px',opacity:'0.5','font-family':'微软雅黑'});
        //msgEntity.addClass(".toast");
        msgEntity.hide();
    },
    //显示动画
    show :function(){
        msgEntity.fadeIn(this.time/2);
        msgEntity.fadeOut(this.time/2);
    }

}