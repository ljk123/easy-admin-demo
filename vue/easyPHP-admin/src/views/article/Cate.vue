<template>
  <div class="collection">
      <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible">
        <el-form :model="dialogData" :rules="rules" status-icon ref="ruleForm">
          <el-form-item label="名称" label-width="60px" prop="name">
            <el-input v-model="dialogData.name" autocomplete="off"></el-input>
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
          label="序号"
          type="index"
          width="55"
          align="center">
        </el-table-column>
        <el-table-column
          prop="name"
          label="名称"
          width="200"
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
              { required: true, message: '请输入分类名称', trigger: 'blur' },
          ],
        }
    }
  },
  mounted(){
      this.getList();
  },
  methods: {
      getList(){
          api.articleCateLists().then(resposon=>{
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
              console.log(data)
              this.tableData=data.data
          })
      },
    handleDelete (index,row) {
        this.$confirm('是否删除'+row.name+'?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          api.delArticleCate(row.id).then(resposon=>{
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
        this.dialogTitle='修改分类'
        this.dialogData={
            id:row.id,
            name:row.name,
        }
    },
    handleAdd(){
        this.dialogFormVisible=true;
        this.dialogTitle='新增分类'
        this.dialogData={
            name:'',
            auth:[]
        }
    },
    submitDialogForm(){
        this.$refs.ruleForm.validate(valid => {
          if (valid) {
              let formData={}
              formData.name=this.dialogData.name
              formData.id=this.dialogData.id || 0
              api.saveArticleCate(formData).then(resposon=>{
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
