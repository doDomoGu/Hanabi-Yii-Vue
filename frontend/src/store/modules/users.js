
const state = {
    count: 0,
    attributes:{
        id: 0,
        username: "",
        password: "",
        name:"",
        mobile: "",
        email: "",
        status: "1",
        verify: "1",
        usergroups: []
    },
    list:[]
};

const actions = {
};

const getters = {
    attributes:state => state.attributes,
    list: state => state.list,
    getCount : state => state.count
};

const mutations = {

};

export default {
    namespaced:true,
    state,
    actions,
    getters,
    mutations
}
