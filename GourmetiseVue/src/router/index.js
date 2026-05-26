import { createRouter, createWebHistory } from "vue-router";
import Index from "@/views/index.vue";
import ContestParams from "@/views/ContestParam.vue";
import signIn from "@/views/sign-in.vue";
import results from "@/views/results.vue";
import CreateConcours from "@/views/ContestParam/CreateConcours.vue";
import SeeConcours from "@/views/ContestParam/SeeConcours.vue";
import signInBaker from "@/views/sign-inBaker.vue";
import connection from "@/views/connection.vue";
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Index,
    },
    {
      path: "/ContestParam",
      name: "ContestParam",
      component: ContestParams,
    },
    {
      path: "/sign-in",
      name: "sign-in",
      component: signIn,
    },
    {
      path: "/results",
      name: "results",
      component: results,
    },
    {
      path: "/CreateConcours",
      name: "CreateConcours",
      component: CreateConcours,
    },
    {
      path: "/SeeConcours",
      name: "SeeConcours",
      component: SeeConcours,
    },
    {
      path: "/sign-inBaker",
      name: "sign-Baker",
      component: signInBaker,
    },
    {
      path: "/connection",
      name: "connection",
      component: connection,
    },
  ],
});

export default router;
