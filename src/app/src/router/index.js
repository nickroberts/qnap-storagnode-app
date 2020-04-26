import Vue from "vue";
import VueRouter from "vue-router";
import Config from "../views/Config.vue";
import Dashboard from "../views/Dashboard.vue";
import Wizard from "../views/Wizard.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Dashbaord",
    component: Dashboard
  },
  {
    path: "/config",
    name: "Config",
    component: Config
  },
  {
    path: "/wizard",
    name: "Wizard",
    component: Wizard
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

export default router;
