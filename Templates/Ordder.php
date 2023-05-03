<?php
if(isset($_GET['_list'])){
  
  if (!function_exists("scandir")) {
  exit(json_encode(['code' => 1, 'msg' => "scandir 函数被禁用，将导致不能显示插件列表，请检查 php.ini", 'data' => []]));
  }
  
  $pluginModel = new PluginModel;
  $pluginList =$pluginModel->getoddr();
 //var_dump($pluginList);

  if(!empty($pluginList)){
  $pluginlists = ['code' => 0, 'msg' => "", 'data' => $pluginList];
  }else{
  $pluginlists = ['code' => 1, 'msg' => "订单为空", 'data' => []];
  }
  
  //print_r($pluginInfo_f);
  exit(json_encode($pluginlists));
}

$pageTitle = '插件列表'; require_once 'Header.php';

?>
  <div class="layui-fluid">   
  <div class="layui-card">
    <div class="layui-card-header">订单列表</div>
    <div class="layui-card-body">
    <div style="padding-bottom: 10px;">
     
    </div>
      
    <table id="plugins_table" lay-filter="plugins_table"></table>  
    
    <script type="text/html" id="PluginName">
      {{#  if(d.PluginURL){ }}
      <div><a href="{{ d.PluginURL }}" target="_blank" class="layui-table-link">{{ d.PluginName }}</a> {{ d.Version }}</div>
      {{#  } else { }}
      {{ d.PluginName }} {{ d.Version }}
      {{#  } }}
    </script>
    <script type="text/html" id="Author">
      {{#  if(d.AuthorURL){ }}
      <div><a href="{{ d.AuthorURL }}" target="_blank" class="layui-table-link">{{ d.Author }}</div>
      {{#  } else { }}
      {{ d.Author }}
      {{#  } }}
    </script>
    <script type="text/html" id="status">
      {{# if (d.start == 0) { }}  
        <span class="layui-badge-rim">提交中</span>
      {{# } else if(d.start == 1) { }}  
        <span class="layui-badge layui-bg-cyan">处理中</span>
      {{# } else { }}  
        <span class="layui-badge layui-bg-blue">已处理</span>
      {{# } }}  
    </script>
     <script type="text/html" id="tancan">
     
        <span class="layui-badge layui-bg-blue">会员【{{ d.tancan }}】月</span>
     
    </script>
    
  
     <script type="text/html" id="certime">
     
        <span class="layui-badge layui-bg-blue">{{layui.util.toDateString(d.certime*1000, 'yyyy-MM-dd HH:mm:ss')}}</span>
     
    </script>
   
  
    </div>
  </div>
  </div>

 <script src="../Templates/assets/layui/layui.js"></script>  
  <script>
  
  layui.use(['table'], function() {
    var $ = layui.jquery, table = layui.table;
  
  function sendPost(url,data){
    $.ajax({type: "POST",url: url,data:data,dataType: "json",
    success: function(result, textStatus, jqXHR){
      if(result.code == '0' && textStatus == 'success'){
      layer.msg('操作成功',{icon: 1});
      table.reload('plugins_table');
      }else{
      layer.msg('接口响应:' + textStatus, {icon: 1}, function(){});
      }
    },
    });
    return false;
  };
  
  function editPlugins(name,file){
    var index = layer.open({
    type: 2
    ,title: '编辑 [' + name + ']'
    ,content: 'PluginMain/edit/' + file
    ,btn: ['保存', '关闭']
    ,yes: function(index, layero){
      var submit = layero.find('iframe').contents().find("#save_pcn");
      submit.click();
    }
    });
    layer.full(index);
  };
  
  $.fn.serializeJson = function () {
    var serializeObj = {};
    $(this.serializeArray()).each(function () {
    serializeObj[this.name] = this.value;
    });
    return serializeObj;
  };
  
  table.render({
    elem: "#plugins_table",
    url: 'Ordder?_list',
    cols: [[
      //{type: "checkbox",align: "left"},
      {field: "username",title: "代开用户名",width: 220},
      {field: "money",title: "金额(U)",width: 150,},
      {field: "tancan",title: "套餐",minWidth: 250,templet: '#tancan'},
      {field: "status",title: "插件状态",width: 100,templet: '#status'},
      {field: "certime",title: "订单时间",width: 170,sort: true,templet: '#certime'},
     
      ]],
    text: "列表为空！"
    });
    
    //监听单元格编辑
    table.on('edit(plugins_table)', function(obj){
      var pcn = obj.data.pcn,status = obj.data.status,newPriority = obj.value;
      //console.log(obj);
      if(status !== 2){
      table.reload('plugins_table');
      return layer.msg('请先启用插件', {icon: 2});
      }
      sendPost('plugins/priority',{pcn:pcn,newPriority:newPriority});
    });
    
    //监听操作按钮
    table.on('tool(plugins_table)', function(obj){
    var data = obj.data;
    var event = obj.event;
    if(event == 'remove'){
    layer.confirm('确定删除该插件全部文件吗？', function(index) {
      sendPost('plugins/' + event,{pcn:data.pcn});
    });
    }else if(event == 'edit'){
    var file = '';
    layer.confirm('请选择[' + data.PluginName +']需要编辑的文件', {
      btn: [data.pcn + '.class.php','settings.html']
    }, function(){
      
      editPlugins(data.PluginName, data.pcn + '/' + '.class.php');
      layer.closeAll('dialog');
    }, function(){
      editPlugins(data.PluginName, data.pcn + '/' + 'settings.html'); 
    });


    }else if(event == 'settings'){
      $.ajax({type: "POST",url: 'plugins/' + event,data:{pcn:data.pcn},
        success: function(result, textStatus, jqXHR){
        if(textStatus == 'success'){
          if(!result) return layer.msg('该插件无设置界面', {icon: 2});
          var index = layer.open({
          type: 1
          ,title: data.PluginName + ' 设置'
          ,content: result.replace('__PCN__',data.pcn)
          ,maxmin: true
          ,area: data.UIarea.split(',')
          ,btn: ['保存', '取消']
          ,yes: function(index, layero){
            var submit = layero.find("#saveSettings");
            submit.click();
          }
          });
        }else{
          layer.msg('接口响应:' + textStatus, {icon: 2});
        }
        },
      });

    }else{
    sendPost('plugins/' + event,{pcn:data.pcn});
    }
  });
  
  var active = {
    edit_php: function(){
    layui.msg(edit_php)
    },
    
    install: function(){
    sendPost('plugins/installAll',{});
    },
    
    uninstall: function(){
    sendPost('plugins/uninstallAll',{});
    },

    enable: function(){
    sendPost('plugins/enableAll',{});
    },

    disable: function(){
    sendPost('plugins/disableAll',{});
    },

    create: function(){
      var index = layer.open({
      type: 1
      ,title: '初始插件信息'
      ,content: $("#createTemp").html()
      ,area: ['550px', '550px']
      ,btn: ['下一步', '取消']
      ,yes: function(index, layero){
      var data = layero.find("form").serializeJson();
      var __PCN__ = data.pcn,
        __PluginName__ = data.PluginName,
        __PluginURL__ = '',
        __Author__ = data.Author,
        __AuthorURL__ = '',
        __Version__ = data.Version,
        __Description__ = data.Description,
        __UIarea__ = data.UIarea;
        
      if(!/^[a-z]+$/ig.test(__PCN__)){
        return layer.msg('插件PCN只能是英文字符!!!', {icon: 2});
      }
      if(__PluginName__ === ''){
        return layer.msg('插件名称不能为空!!!', {icon: 2});
      }

      if(__PluginName__.indexOf(",") != -1){
        __PluginName__ = data.PluginName.split(',')[0];
        __PluginURL__ = data.PluginName.split(',')[1];
      }
      
      if(__Author__.indexOf(",") != -1){
        __Author__ = data.Author.split(',')[0];
        __AuthorURL__ = data.Author.split(',')[1];
      }
      
      $.get('PluginCreate',function(result,textStatus){
        if(textStatus == 'success'){
           let code = result.replace(/(__PCN__|__PluginName__|__PluginURL__|__Author__|__AuthorURL__|__Version__|__Description__|__UIarea__)/gi, function ($0, $1) {
            return {
            "__PCN__": __PCN__,
            "__PluginName__": __PluginName__,
            "__PluginURL__": __PluginURL__,
            "__Author__": __Author__,
            "__AuthorURL__": __AuthorURL__,
            "__Version__": __Version__,
            "__Description__": __Description__,
            "__UIarea__": __UIarea__,
            }[$1];
          });
          var index = layer.open({
          type: 1
          ,title: '创建插件 [' + __PCN__ + '.class.php]'
          ,content: code
          ,maxmin: true
          ,area: ['80%','80%']
          ,btn: ['确定', '取消']
          ,yes: function(index, layero){
            var submit = layero.find("#createSub");
            submit.click();
          }
          });
          layer.full(index);
        }else{
          layer.msg('接口响应:' + textStatus, {icon: 2});
        }
      });
      }
    });
  },


  }
  $('.layui-btn.layuiadmin-btn-role').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });
  });
  </script>
<?php require_once 'Footer.php' ?>