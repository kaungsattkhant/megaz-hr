export default {
    state() {
        return {
            user: null,
            token: null,
            department: null,
            roles: [],
            csrfToken: null
        }
    },

    mutations: {
        setUser(state, user){
            state.user = user;
        },

        setToken(state, token){
            state.token = token;
        },

        setDepartment(state, department){
            state.department = department;
        },

        setRoles(state, roles){
            state.roles = roles;
        },

        setCsrfToken(state, csrfToken){
            state.csrfToken = csrfToken;
        },

        setFeature(state, feature){
            state.feature = feature;
        },
    },

    actions: {},

    getters: {
        getUser(state){
            return state.user;
        },

        getToken(state){
            return state.token;
        },

        getDepartment(state){
            return state.department;
        },

        getRoles(state){
            return state.roles;
        },

        getCsrfToken(state){
            return state.csrfToken;
        },

        getFeature(state){
            return state.feature;
        }
    }
};
