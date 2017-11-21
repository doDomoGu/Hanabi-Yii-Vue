<template>
    <el-row class="user-main">
        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
            <el-form-item label="用户名">
                <el-input v-model="searchForm.username" placeholder="用户名"></el-input>
            </el-form-item>
            <el-form-item label="手机">
                <el-input v-model="searchForm.mobile" placeholder="手机"></el-input>
            </el-form-item>
            <!--<el-form-item label="状态">
                <el-select v-model="searchForm.status" placeholder="状态">
                    <el-option label="&#45;&#45;" value=""></el-option>
                    <el-option label="正常" value="1"></el-option>
                    <el-option label="禁用" value="0"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="审核状态">
                <el-select v-model="searchForm.verify" placeholder="审核状态">
                    <el-option label="&#45;&#45;" value=""></el-option>
                    <el-option label="审核通过" value="1"></el-option>
                    <el-option label="待审核" value="0"></el-option>
                    <el-option label="审核不通过" value="2"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="名称" label-width="55px">
                <el-input v-model="searchForm.name" placeholder="名称"></el-input>
            </el-form-item>
            <el-form-item label="邮箱">
                <el-input v-model="searchForm.email" placeholder="邮箱"></el-input>
            </el-form-item>-->
            <el-form-item>
                <el-button type="primary" @click="onSearch">查询</el-button>
                <el-button type="info" @click="onReset">重置</el-button>
                <el-button type="success" @click="showAddDialog">添加用户</el-button>
            </el-form-item>
        </el-form>

        <el-dialog title="添加用户" :visible.sync="addDialogVisible">
            <el-form :model="addForm" label-width="120px">
                <el-form-item label="用户名">
                    <el-input v-model="addForm.username" placeholder="用户名"></el-input>
                </el-form-item>
                <el-form-item label="密码">
                    <el-input v-model="addForm.password" placeholder="密码"></el-input>
                </el-form-item>
                <el-form-item label="名称">
                    <el-input v-model="addForm.name" placeholder="名称"></el-input>
                </el-form-item>
                <el-form-item label="手机">
                    <el-input v-model="addForm.mobile" placeholder="手机"></el-input>
                </el-form-item>
                <el-form-item label="邮箱">
                    <el-input v-model="addForm.email" placeholder="邮箱"></el-input>
                </el-form-item>
                <el-form-item label="用户组">
                    <el-checkbox-group v-model="addForm.usergroups" >
                        <el-checkbox :label="ug.id" :key="ug.id"  v-for="ug in usergroups">{{ug.name}}</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
                <el-form-item label="状态">
                    <el-select v-model="addForm.status" placeholder="状态">
                        <el-option label="正常" value="1"></el-option>
                        <el-option label="禁用" value="0"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="审核状态">
                    <el-select v-model="addForm.verify" placeholder="审核状态">
                        <el-option label="审核通过" value="1"></el-option>
                        <el-option label="待审核" value="0"></el-option>
                        <el-option label="审核失败" value="2"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closeAddDialog">取 消</el-button>
                <el-button type="primary" @click="onAddSubmit">确 定</el-button>
            </span>
        </el-dialog>


        <el-table id="user-table" v-loading="loading" element-loading-text="拼命加载中" :data="tableData" stripe style="width:100%">
            <el-table-column prop="id" label="ID" width="80"></el-table-column>
            <el-table-column prop="username" label="用户名" width="180"></el-table-column>
            <el-table-column prop="name" label="名称" width="180"></el-table-column>
            <el-table-column prop="mobile" label="手机" width="180"></el-table-column>
            <el-table-column prop="email" label="邮箱" width="180"></el-table-column>
            <el-table-column prop="usergroups" label="所属用户组" width="180"></el-table-column>
            <el-table-column prop="status" :formatter="statusFormat" label="状态"></el-table-column>
            <el-table-column prop="verify" :formatter="verifyFormat" label="审核"></el-table-column>
            <el-table-column prop="operation" :formatter="operationFormat" label="操作"></el-table-column>
        </el-table>

        <el-pagination class="pager" layout="prev, pager, next, total" v-show="tableData.length>0" :page-size="pageSize" :total="total" @current-change="onPageChange" :current-page="page">
        </el-pagination>
    </el-row>
</template>

<script>
/*import { mapActions,mapMutations } from 'vuex'*/
import axios from '../../../axios'
import qs from 'qs'

export default {
    name: 'user_index',
    data() {
        return {
            addDialogVisible:false,
            searchForm: {},// this.$store.getters['search/users'],
            tableData: [],//this.$store.state.users.list
            addForm: {},
            total:0,
            page:1,
            pageSize:10,
            usergroups:[]
        }
    },
    created(){
        this.loading = true;

        //读取store中搜索条件
        let s = this.$store.getters['search/default_users'];
        for(let i in s){
            if(s.hasOwnProperty(i)){
                //对searchForm字段初始化
                this.$set(this.searchForm,i,s[i]);
            }
        }

        //读取store中users模型
        var m = this.$store.getters['users/attributes'];
        for(var j in m){
            //对addForm字段初始化
            this.$set(this.addForm,j,m[j]);
        }

        //根据搜索条件查询列表数据
        this.getData(this.searchForm);
    },
    methods:{
        onSearch:function(){
            this.loading = true;
            //保存搜索条件到store
            var s = {};
            for(var i in this.searchForm){
                s[i] = this.searchForm[i];
            }
            this.$store.dispatch('search/UpdateUsers', s);

            this.getData(s);
        },
        onRefresh: function(){
            this.loading = true;
            //读取store里的搜索条件
            this.getData(this.$store.getters['search/default_users']);
        },
        onReset: function(){
            this.loading = true;
            //重置搜索条件
            this.$store.dispatch('search/ResetUsers');
            //重新获取初始条件
            let s = this.$store.getters['search/users'];
            for(let i in s){
                if(s.hasOwnProperty(i)){
                    console.log('reset: ',i,s[i]);
                    //对searchForm字段初始化
                    this.$set(this.searchForm,i,s[i])
                }
            }
            this.page = 1;

            this.onRefresh();
        },
        onPageChange: function(v){
            this.page = v;

            this.getData(this.searchForm);
        },
        showAddDialog: function(){
            axios.get('/usergroups')
            .then((res) => {
                var _res = res.data;

                this.usergroups = _res.data;
//console.log(_res.data);
                this.addDialogVisible = true;

            })
            .catch(function(err){
                console.log(err);
                this.usergroups = [];
                this.addDialogVisible = true;
            })


        },
        closeAddDialog: function(){
            var m = this.$store.getters['users/attributes'];
            for(var j in m){
                //对addForm字段初始化
                this.$set(this.addForm,j,m[j])
            }
            this.addDialogVisible = false;
        },
        onAddSubmit: function(){
            var usergroup_ids = this.addForm.usergroups;


            axios.post(
                '/users',
                this.addForm
            )
            .then((res) => {
                if(res && res.data && res.data.id>0) {
                    /*var usergroup_ids = [];
                    for (var i in this.addForm.usergroups) {
                        usergroup_ids.push(i);
                    }
                    console.log(usergroup_ids);
                    return false;*/
                    axios.post(
                        '/users/'+res.data.id+'/usergroups',
                        {
                            data: qs.stringify({
                                usergroup_ids: usergroup_ids
                            })
                        }
                    )
                    .then((res) => {
                        this.closeAddDialog();
                        this.$message({
                            message: '添加成功！',
                            type: 'success'
                        });
                        this.onRefresh();
                    })
                    .catch(function (err) {
                        console.log(err);
                    })
                }
            })
            .catch(function(err){
                console.log(err);
            })

        },
        sexFormat:function(r, c, v) {
            return  v==1?'男':(v==2?'女':'N/A');
        },
        statusFormat:function(r, c, v) {
            return  v==1?'正常':'禁用';
        },
        verifyFormat:function(r, c, v) {
            return  v==1?'审核通过':(v==2?'审核不通过':'待审核');
        },
        operationFormat:function(r, c, v) {
            return  '---';
        },
        getData: function (params) {
            var that = this;
            that.loading = true;
            params.page = that.page;
            params.pageSize = that.pageSize;
            axios.get('/users',{
                params:params
            })
            .then((res) => {
                var UserRes = res.data;
                if(UserRes.data.length>0){
                    var userids = [];
                    for(var i in UserRes.data){
                        userids.push(UserRes.data[i].id);
                    }
                    axios.get('/users/usergroups', {
                        params: {
                            userids: userids.join(',')
                        }
                    })
                    .then((res) => {
                        for(var i in UserRes.data){
                            UserRes.data[i].usergroups = res.data.data[UserRes.data[i].id]?res.data.data[UserRes.data[i].id].join(','):'';
                        }
                        that.tableData = UserRes.data;
                        that.total = UserRes.total_count;
                        that.loading = false;
                    })
                    .catch(function(err){
                        console.log(err);
                        that.tableData = [];
                        that.total = 0;
                        that.loading = false;
                    })
                }else{
                    that.tableData = [];
                    that.total = 0;
                    that.loading = false;
                }
            })
            .catch(function(err){
                console.log(err);
                that.tableData = [];
                that.total = 0;
                that.loading = false;
            })
        }
    }
}
</script>

<style>
/*.user-main {
    padding:24px;
}*/
#get-data-btn {
    margin-top:20px;
}
.pager {
    margin-top:20px;
    text-align: center;
}
</style>
