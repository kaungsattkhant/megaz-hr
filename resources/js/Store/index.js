import { createStore } from "vuex";
import createPersistedState from 'vuex-persistedstate';

import AuthStore from "./modules/AuthStore";

export const store = createStore({
    modules: {
        AuthStore
    },
    plugins: [createPersistedState()]
});
