<template>
  <div class="collection">
      <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible" @open="handleOpenDialog">
          <el-form :model="dialogData" :rules="rules" status-icon ref="ruleForm">
            <el-form-item label="用户名" label-width="90px" prop="username">
              <el-input v-model="dialogData.username" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="密码" label-width="90px" prop="pwd">
              <el-input v-model="dialogData.pwd" autocomplete="off" show-password></el-input>
            </el-form-item>
            <el-form-item label="所属分组" label-width="90px" prop="group_id">
              <el-select v-model="dialogData.group_id" placeholder="请选择">
                  <el-option
                    v-for="item in groups"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="状态" label-width="90px" prop="status">
              <el-checkbox v-model="dialogData.status">生效</el-checkbox>
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
          prop="username"
          label="用户名"
          width="100"
          align="center">
        </el-table-column>
        <el-table-column
          prop="group_name"
          label="分组"
          width=""
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
          prop="login_time_format"
          label="最近登陆时间"
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
          <!-- <el-pagination
            layout="prev, pager, next"
            @current-change="handleCurrentChange"
            :total="50">
          </el-pagination> -->
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
      groups:[],
        rules: {
          username: [
              { required: true, message: '请输入用户名', trigger: 'blur' },
              { min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'blur' }
          ],
          group_id: [
              { required: true, message: '请选择分组', trigger: 'change' },
          ],
        }
    }
  },
  mounted(){
      this.getList();
  },
  methods: {
    getList(){
      api.userLists().then(resposon=>{
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
      })},
    handleDelete (index,row) {
        this.$confirm('是否删除'+row.username+'?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          api.delUser(row.id).then(resposon=>{
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
        this.dialogTitle='修改账号'
        this.dialogData={
            id:row.id,
            username:row.username,
            pwd:'',
            group_id:row.group_id,
            status:row.status===1
        }
    },
    
    handleAdd(){
        this.dialogFormVisible=true;
        this.dialogTitle='新增账号'
        this.dialogData={
            username:'',
            pwd:'',
            group_id:'',
            status:true
        }
    },
    handleOpenDialog(){
        if(this.groups.length===0)
        {
            api.userGroupLists().then(resposon=>{
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
                this.groups=data.data
                this.handleOpenDialog()
            })
            return
        }
    },
    submitDialogForm(){
        this.$refs.ruleForm.validate(valid => {
          if (valid) {
              let formData={}
              formData.username=this.dialogData.username
              formData.status=this.dialogData.status?1:0
              formData.group_id=this.dialogData.group_id
              formData.pwd=this.dialogData.pwd || ''
              formData.id=this.dialogData.id || 0
              api.saveUser(formData).then(resposon=>{
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
