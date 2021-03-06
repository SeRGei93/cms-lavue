export const state = () => ({
    page: null,
    module_name: null,
    item: null
})

// getters
export const getters = {
    page: state => state.page,
    module: state => state.module_name,
    item: state => state.item
}

// mutations
export const mutations = {
    SET_PAGE(state, {page}) {
        state.page = page;
    },
    SET_MODULE(state, {module}) {
        state.module_name = module;
    },
    SET_ITEM(state, {item}) {
        state.item = item;
    },
}

// actions
export const actions = {
    setPage({commit}, page) {
        commit('SET_PAGE', {page})
    },
    setModule({commit}, module) {
        commit('SET_MODULE', {module})
    },
    setItem({commit}, item) {
        commit('SET_ITEM', {item})
    }
}