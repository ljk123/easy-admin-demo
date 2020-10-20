<template>
  <div class="collection">
      <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible" @open="handleOpenDialog">
        <el-form :model="dialogData" :rules="rules" status-icon ref="ruleForm">
          <el-form-item label="名称" label-width="60px" prop="name">
            <el-input v-model="dialogData.name" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="权限" label-width="60px">
            <el-tree
              ref="tree"
              :check-on-click-node="true"
              :expand-on-click-node="false"
              node-key="authId"
              :default-expand-all="true"
              :props="props"
              :data="auth"
              :check-strictly="checkStrictly"
              show-checkbox
              >
            </el-tree>
          </el-form-item>
          <el-form-item label="描述" label-width="60px">
            <el-input v-model="dialogData.desc" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="submitDialogForm">确 定</el-button>
        </div>
      </el-dialog>
    <el-button type="primary" icon="el-icon-plus" @click="handleAdd">添加</el-button>   
    <div class="table-box grid-content bg-white">
      <el-table
        ref="multipleTable"
        :data="tableData"
        tooltip-effect="dark"
        style="width: 100%;margin-top: 20px;">
        <el-table-column
          fixed
          label="序号"
          type="index"
          width="55"
          align="center">
        </el-table-column>
        <el-table-column
          fixed
          prop="name"
          label="名称"
          width="100"
          align="center">
        </el-table-column>
        <el-table-column
          prop="desc"
          label="备注"
          width="180"
          align="center">
        </el-table-column>
        <el-table-column
          prop="create_time_format"
          label="创建时间"
          width="180"
          align="center">
        </el-table-column>
        <el-table-column
          label="操作"
          width="300"
          align="center">
          <template slot-scope="scope">
            <el-button type="primary" size="small" @click="handleEdit(scope.row)">编辑</el-button>
            <el-button type="danger" size="small" @click="handleDelete(scope.$index,scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import api from '@/api.js'
export default {
  name: 'Collection',
  data () {
    return {
      tableData: [],
      dialogFormVisible:false,
      dialogTitle:'',
      dialogData:{},
      checkStrictly:false,
      auth:[],
        props: {
          children: 'children',
          label: 'label'
        },
        rules: {
          name: [
              { required: true, message: '请输入分组名称', trigger: 'blur' },
          ],
        }
    }
  },
  mounted(){
      this.getList();
  },
  methods: {
      getList(){
          api.userGroupLists().then(resposon=>{
              let data=resposon.data
              if(data.status==0)
              {
                  this.$message({
                    message: data.msg,
                    duration: 3000,
                    type: 'error'
                  })
                  return
              }
              this.tableData=data.data
          })
      },
    handleDelete (index,row) {
        this.$confirm('是否删除'+row.name+'?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          api.delUserGroup(row.id).then(resposon=>{
              let {data}=resposon
              if(data.status==0)
              {
                  this.$message({
                    message: data.msg,
                    duration: 3000,
                    type: 'error'
                  })
                  return
              }
              this.tableData.splice(index,1);
          })
        })
    },
    handleEdit (row) {
        this.dialogFormVisible=true;
        this.dialogTitle='修改分组'
        this.dialogData={
            id:row.id,
            name:row.name,
            desc:row.desc,
            auth:row.auth
        }
    },
    handleAdd(){
        this.dialogFormVisible=true;
        this.dialogTitle='新增分组'
        this.dialogData={
            name:'',
            desc:'',
            auth:[]
        }
    },
    handleOpenDialog(){
        if(this.auth.length===0)
        {
            api.allRoles().then(resposon=>{
                let {data}=resposon
                if(data.status==0)
                {
                    this.$message({
                      message: data.msg,
                      duration: 3000,
                      type: 'error'
                    })
                    return
                }
                let auth=[];
                for(let k in data.data)
                {
                    auth.push(data.data[k])
                }   
                this.auth=array2tree(data.data)
                this.handleOpenDialog()
            })
            return
        }
        this.$refs.tree.setCheckedKeys([])
        //不知道为什么 延迟才生效
        setTimeout(()=>{this.dialogData.auth.forEach(val=>{
            this.$refs.tree.setChecked(val,true)
        })},0)
        
    },
    submitDialogForm(){
        this.$refs.ruleForm.validate(valid => {
          if (valid) {
              let formData={}
              formData.name=this.dialogData.name
              formData.desc=this.dialogData.desc || ''
              formData.id=this.dialogData.id || 0
              formData.auth=this.$refs.tree.getCheckedKeys().concat(this.$refs.tree.getHalfCheckedKeys())
              api.saveUserGroup(formData).then(resposon=>{
                  let {data}=resposon
                  if(data.status==0)
                  {
                      this.$message({
                        message: data.msg,
                        duration: 3000,
                        type: 'error'
                      })
                      return
                  }
                  this.dialogFormVisible=false
                  this.getList()
              })
          }
        })
        
    }
  }
}
const in_tree_obj=(obj,label)=>{
    for(let i=0;i<obj.length;i++)
    {
        if(obj[i].label===label)
        {
            return i
        }
    }
    return false
}
const array2tree=(array)=>{
    let tree=[]
    for(let authId in array)
    {
        let item=array[authId],treeCopy=tree
       for(let level=0;level<item.length;level++)
       {
           let pos=in_tree_obj(treeCopy,item[level])
           if(false!==pos)
           {
               if(undefined===treeCopy[pos].children)
               {
                   treeCopy[pos].children=[]
               }
               treeCopy=treeCopy[pos].children
           }
           else{
               treeCopy.push({label:item[level],authId})
               level--
           }
       } 
    }
    return tree
}
</script>

<style lang="stylus">
  .collection
    // max-width 1100px
    margin 40px auto
    .new
      width 100%
      margin-bottom 20px
      height 40px
      background #fff
      display flex
      align-items center
      justify-content center
      cursor pointer
      i
        color #03A9F4
        padding-right 10px
    .page
      height 40px
      margin-top 20px
      display flex
      align-items center
      justify-content flex-end
      .el-pagination
        background #fff

</style>
