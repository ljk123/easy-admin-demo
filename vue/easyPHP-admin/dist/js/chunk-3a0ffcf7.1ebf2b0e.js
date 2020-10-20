(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3a0ffcf7"],{"4db0":function(t,e,a){"use strict";var l=a("7bed"),i=a.n(l);i.a},"7bed":function(t,e,a){},eeca:function(t,e,a){"use strict";a.r(e);var l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"collection"},[a("el-dialog",{attrs:{title:t.dialogTitle,visible:t.dialogFormVisible},on:{"update:visible":function(e){t.dialogFormVisible=e},open:t.handleOpenDialog}},[a("el-form",{ref:"ruleForm",attrs:{model:t.dialogData,rules:t.rules,"status-icon":""}},[a("el-form-item",{attrs:{label:"用户名","label-width":"90px",prop:"username"}},[a("el-input",{attrs:{autocomplete:"off"},model:{value:t.dialogData.username,callback:function(e){t.$set(t.dialogData,"username",e)},expression:"dialogData.username"}})],1),a("el-form-item",{attrs:{label:"密码","label-width":"90px",prop:"pwd"}},[a("el-input",{attrs:{autocomplete:"off","show-password":""},model:{value:t.dialogData.pwd,callback:function(e){t.$set(t.dialogData,"pwd",e)},expression:"dialogData.pwd"}})],1),a("el-form-item",{attrs:{label:"所属分组","label-width":"90px",prop:"group_id"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.dialogData.group_id,callback:function(e){t.$set(t.dialogData,"group_id",e)},expression:"dialogData.group_id"}},t._l(t.groups,function(t){return a("el-option",{key:t.id,attrs:{label:t.name,value:t.id}})}),1)],1),a("el-form-item",{attrs:{label:"状态","label-width":"90px",prop:"status"}},[a("el-checkbox",{model:{value:t.dialogData.status,callback:function(e){t.$set(t.dialogData,"status",e)},expression:"dialogData.status"}},[t._v("生效")])],1)],1),a("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("取 消")]),a("el-button",{attrs:{type:"primary"},on:{click:t.submitDialogForm}},[t._v("确 定")])],1)],1),a("el-button",{attrs:{type:"primary",icon:"el-icon-plus"},on:{click:t.handleAdd}},[t._v("添加")]),a("div",{staticClass:"table-box grid-content bg-white"},[a("el-table",{ref:"multipleTable",staticStyle:{width:"100%","margin-top":"20px"},attrs:{data:t.tableData,"tooltip-effect":"dark"}},[a("el-table-column",{attrs:{fixed:"",label:"序号",type:"index",width:"55",align:"center"}}),a("el-table-column",{attrs:{fixed:"",prop:"username",label:"用户名",width:"100",align:"center"}}),a("el-table-column",{attrs:{prop:"group_name",label:"分组",width:"",align:"center"}}),a("el-table-column",{attrs:{prop:"status_word",label:"状态",width:"60",align:"center"}}),a("el-table-column",{attrs:{prop:"create_time_format",label:"创建时间",width:"180",align:"center"}}),a("el-table-column",{attrs:{prop:"login_time_format",label:"最近登陆时间",width:"180",align:"center"}}),a("el-table-column",{attrs:{label:"操作",width:"300",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(a){t.handleEdit(e.row)}}},[t._v("编辑")]),a("el-button",{attrs:{type:"danger",size:"small"},on:{click:function(a){t.handleDelete(e.$index,e.row)}}},[t._v("删除")])]}}])})],1)],1)],1)},i=[],o=a("d722"),s={name:"Collection",data:function(){return{tableData:[],dialogFormVisible:!1,dialogTitle:"",dialogData:{},groups:[],rules:{username:[{required:!0,message:"请输入用户名",trigger:"blur"},{min:3,max:20,message:"长度在 3 到 20 个字符",trigger:"blur"}],group_id:[{required:!0,message:"请选择分组",trigger:"change"}]}}},mounted:function(){this.getList()},methods:{getList:function(){var t=this;o["a"].userLists().then(function(e){var a=e.data;0!=a.status?t.tableData=a.data:t.$message({message:a.msg,duration:3e3,type:"error"})})},handleDelete:function(t,e){var a=this;this.$confirm("是否删除"+e.username+"?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){o["a"].delUser(e.id).then(function(e){var l=e.data;0!=l.status?a.tableData.splice(t,1):a.$message({message:l.msg,duration:3e3,type:"error"})})})},handleEdit:function(t){this.dialogFormVisible=!0,this.dialogTitle="修改账号",this.dialogData={id:t.id,username:t.username,pwd:"",group_id:t.group_id,status:1===t.status}},handleAdd:function(){this.dialogFormVisible=!0,this.dialogTitle="新增账号",this.dialogData={username:"",pwd:"",group_id:"",status:!0}},handleOpenDialog:function(){var t=this;0!==this.groups.length||o["a"].userGroupLists().then(function(e){var a=e.data;0!=a.status?(t.groups=a.data,t.handleOpenDialog()):t.$message({message:a.msg,duration:3e3,type:"error"})})},submitDialogForm:function(){var t=this;this.$refs.ruleForm.validate(function(e){if(e){var a={};a.username=t.dialogData.username,a.status=t.dialogData.status?1:0,a.group_id=t.dialogData.group_id,a.pwd=t.dialogData.pwd||"",a.id=t.dialogData.id||0,o["a"].saveUser(a).then(function(e){var a=e.data;0!=a.status?(t.dialogFormVisible=!1,t.getList()):t.$message({message:a.msg,duration:3e3,type:"error"})})}})}}},r=s,n=(a("4db0"),a("2877")),d=Object(n["a"])(r,l,i,!1,null,null,null);d.options.__file="User.vue";e["default"]=d.exports}}]);
//# sourceMappingURL=chunk-3a0ffcf7.1ebf2b0e.js.map