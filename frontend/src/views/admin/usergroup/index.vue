<template>
    <el-row class="user-main">
        <!--<el-form :inline="true" :model="searchForm" class="demo-form-inline">
            <el-form-item label="用户名">
                <el-input v-model="searchForm.username" placeholder="用户名"></el-input>
            </el-form-item>
            <el-form-item label="手机">
                <el-input v-model="searchForm.mobile" placeholder="手机"></el-input>
            </el-form-item>
            <el-form-item label="状态">
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
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSearch">查询</el-button>
            </el-form-item>
        </el-form>-->


        <el-table id="user-table" v-loading="loading" element-loading-text="拼命加载中" :data="tableData" stripe style="width:100%">
            <el-table-column prop="id" label="ID" width="80"></el-table-column>
            <el-table-column prop="name" label="名称" width="180"></el-table-column>
            <el-table-column prop="alias" label="别名" width="180"></el-table-column>
            <el-table-column prop="remark" label="备注" width="180"></el-table-column>
            <el-table-column prop="user_num" label="用户数" width="180"></el-table-column>
            <el-table-column prop="status" :formatter="statusFormat" label="状态"></el-table-column>
            <el-table-column prop="operation" :formatter="operationFormat" label="操作"></el-table-column>
        </el-table>

        <el-pagination class="pager" layout="prev, pager, next, total" v-show="tableData.length>0" :page-size="pageSize" :total="total" @current-change="onPageChange" :current-page="page">
        </el-pagination>

    </el-row>
</template>

<script>
import { mapActions,mapMutations } from 'vuex'
import axios from '../../../axios'

export default {
    name: 'usergroup_index',
    data() {
        /*return {
            tableData: this.getData()
        }*/
        return {
            //searchForm: {},
            tableData: [],
            total:0,
            page:1,
            pageSize:10,
            //addDialogVisible:false
            //addForm: {},
        }
    },
    created(){
        this.loading = false;
        this.getData({});
    },
    methods:{
        onSearch:function(){
            console.log('search');
        },
        onPageChange: function(v){
            this.page = v;

            this.getData({});
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

            axios.get('/usergroups',{
                params:params
            })
            .then((res) => {
                var _res = res.data;
                if(_res.data.length>0){
                    var usergroup_ids = [];
                    for(var i in _res.data){
                        usergroup_ids.push(_res.data[i].id);
                    }
                    axios.get('/usergroups/users', {
                        params: {
                            usergroup_ids: usergroup_ids.join(',')
                        }
                    })
                        .then((res) => {
                            for(var i in _res.data){
                                _res.data[i].user_num = res.data.data[_res.data[i].id]?res.data.data[_res.data[i].id].length:0;
                            }
                            that.tableData = _res.data;
                            that.total = _res.total_count;
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
        /*mapMutations([
            'LIST'=>(this.$store)
                   ])*/

    },
    computed: {
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
