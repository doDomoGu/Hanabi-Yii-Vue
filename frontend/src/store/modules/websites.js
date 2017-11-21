const module_websites = {
  state: {
    count: 20,
    count2: 20,
    data:[]
  },
  mutations: {
    increment (state) {
      state.count2++
    }
  },
  actions: {},
  getters: {
      doneTodosCount22: (state, getters) => {
          return getters.doneTodos.length
      },
      doneTodos3: state => {
          return state.todos.filter(xx => !xx.done)
      }
  }
}

export default module_websites
