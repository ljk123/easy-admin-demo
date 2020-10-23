<template>
  <div class="collection">
      <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible" @open="handleOpenDialog">
        <el-form :model="dialogData" :rules="rules" status-icon ref="ruleForm">
          <el-form-item label="标题" label-width="90px" prop="title">
            <el-input v-model="dialogData.title" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="所属分类" label-width="90px" prop="cid">
            <el-select v-model="dialogData.cid" placeholder="请选择">
                <el-option
                  v-for="item in cates"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
          </el-form-item>
          <el-form-item label="作者" label-width="90px" prop="author">
            <el-input v-model="dialogData.author" autocomplete="off" size="mini"></el-input>
          </el-form-item>
          <el-form-item label="状态" label-width="90px" prop="status">
            <el-checkbox v-model="dialogData.status">生效</el-checkbox>
          </el-form-item>
          <el-form-item label="内容" label-width="90px" prop="content">
              <mavon-editor v-model="dialogData.content"/>
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
          prop="title"
          label="标题"
          width="100"
          align="center">
        </el-table-column>
        <el-table-column
          prop="cate_name"
          label="所属分类"
          width="180"
          align="center">
        </el-table-column>
        <el-table-column
          prop="status_word"
          label="状态"
          width="60"
          align="center">
        </el-table-column>
        <el-table-column
          prop="create_time_format"
          label="创建时间"
          width="180"
          align="center">
        </el-table-column>
        <el-table-column
          prop="update_time_format"
          label="更新时间"
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
      cates:[],
        rules: {
          title: [
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
          api.articleLists().then(resposon=>{
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
        this.$confirm('是否删除'+row.title+'?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          api.delArticle(row.id).then(resposon=>{
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
        this.dialogTitle='修改文章'
        this.dialogData={
            id:row.id,
            title:row.title,
            cid:row.cid,
            content:row.content,
            author:row.author,
            status:row.status===1,
        }        
    },
    handleAdd(){
        this.dialogFormVisible=true;
        this.dialogTitle='新增文章'
        this.dialogData={
            title:"",
            cid:"",
            content:"",
            author:"",
            status:true,
        }
    },
    handleOpenDialog(){
        if(this.cates.length===0)
        {
            api.articleCateLists().then(resposon=>{
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
                this.cates=data.data
                this.handleOpenDialog()
            })
            return
        }
    },
    submitDialogForm(){
        this.$refs.ruleForm.validate(valid => {
          if (valid) {
              let formData={}
              formData.title=this.dialogData.title
              formData.cid=this.dialogData.cid
              formData.content=this.dialogData.content
              formData.author=this.dialogData.author
              formData.status=this.dialogData.status?1:0
              formData.id=this.dialogData.id || 0
              api.saveArticle(formData).then(resposon=>{
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
