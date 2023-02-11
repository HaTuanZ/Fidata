import VueRouter from "vue-router";
import Analysis from "./components/Analysis";
import Project from "./components/projects/Project";
import Account from "./components/Account";

const routes = [
    {
        path: "/analysis",
        component: Analysis,
        name: "analysis"
    },
    {
        path: "/analysis/market-overview/:slug?",
        component: Analysis,
        name: "analysis-market-overview"
    },
    {
        path: "/analysis/project/:slug?",
        component: Analysis,
        name: "analysis-project"
    },
    {
        path: "/project/:slug?",
        component: Project,
        name: "project"
    },
    {
        path: "/account/my-gems",
        component: Account,
        name: "account-my-gems"
    },
    {
        path: "/account/activity/:slug?",
        component: Account,
        name: "account-activity"
    },
    {
        path: "/account/gem-rewards",
        component: Account,
        name: "account-gem-rewards"
    },
    {
        path: "/account/deposit",
        component: Account,
        name: "account-deposit"
    },
    {
        path: "/account/balance",
        component: Account,
        name: "account-balance"
    },
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;
