import Vue from "vue";
import App from "./App.vue";

// Import UI komponent
import Buefy from "buefy";
import "./assets/css/main.scss";

Vue.use(Buefy);

// Import JS knihovny MomentJS - práce s datumy a časem
import moment from "moment";
Vue.prototype.moment = moment;
moment.locale("cs-CZ");

new Vue({
  render: h => h(App)
}).$mount("#app");
