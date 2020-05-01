import Vue from 'vue';
import VueRouter from 'vue-router';
import Setup from '../views/Setup.vue';
import Dashboard from '../views/Dashboard.vue';
import Wizard from '../views/Wizard.vue';
import WeeWizard from '../views/WeeWizard.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'Dashbaord',
    component: Dashboard
  },
  {
    path: '/setup',
    name: 'Setup',
    component: Setup
  },
  {
    path: '/wizard',
    name: 'Wizard',
    component: Wizard
  },
  {
    path: '/wee-wizard',
    name: 'WeeWizard',
    component: WeeWizard
  },
  {
    path: '*',
    redirect: '/'
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router;
