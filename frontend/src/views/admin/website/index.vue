<template>
    <div class="website-main">
        <el-table id="website-table" v-loading="loading" element-loading-text="拼命加载中" :data="tableData" stripe style="width:100%">
            <el-table-column prop="id" label="ID" width="80"></el-table-column>
            <el-table-column prop="alias" label="站点别名" width="180"></el-table-column>
            <el-table-column prop="name" label="站点名称" width="180"></el-table-column>
            <el-table-column prop="status" :formatter="statusFormat" label="状态"></el-table-column>
        </el-table>

        <el-pagination class="pager" layout="prev, pager, next, total" v-show="tableData.length>0" :page-size="pageSize" :total="total" @current-change="onPageChange" :current-page="page">
        </el-pagination>
    </div>
</template>

<script>
//import { mapActions,mapMutations } from 'vuex'
import axios from '../../../axios'

export default {
    name: 'website_list',
    data() {
        return {
            //addDialogVisible:false,
            searchForm: {},// this.$store.getters['search/users'],
            tableData: [],//this.$store.state.users.list
            //addForm: {},
            total:0,
            page:1,
            pageSize:10,
            //usergroups:[]
        }
    },
    created(){
        this.loading = true;

        //读取store中搜索条件
        /*var s = this.$store.getters['search/websites'];
        for(var i in s){
            //对searchForm字段初始化
            this.$set(this.searchForm,i,s[i]);
        }*/

        //根据搜索条件查询列表数据
        this.getData(this.searchForm);

    },
    methods:{
        statusFormat:function(r, c, v) {
            return  v==1?'正常':'禁用';
        },
        onPageChange: function(v){
            this.page = v;

            this.getData(this.searchForm);
        },
        getData: function (params) {
            var that = this;
            that.loading = true;
            params.page = that.page;
            params.pageSize = that.pageSize;
            axios.get('/websites',{
                params:params
            })
                .then((res) => {


                    that.tableData = res.data.data;
                    that.total = res.data.total_count;
                    that.loading = false;



                    /*var UserRes = res.data;
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
                    }*/
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
    .pager {
        margin-top:20px;
        text-align: center;
    }
</style>
