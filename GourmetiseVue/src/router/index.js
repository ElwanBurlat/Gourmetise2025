import {createRouter, createWebHistory} from 'vue-router'
import Index from "@/views/index.vue";
import ContestParams from '@/views/ContestParam.vue';
import signIn from '@/views/sign-in.vue';



const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Index,
        },
        {
            path: '/ContestParam',
            name: 'ContestParam',
            component: ContestParams,
        },
        {
            path: '/sign-in',
            name: 'sign-in',
            component: signIn,
        }
    ],
})

export default router
