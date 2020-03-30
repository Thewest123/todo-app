<template>
  <div id="app">
    <todo-header></todo-header>
    <todo-table></todo-table>
  </div>
</template>

<script>
import TodoTable from "@/components/TodoTable.vue";
import TodoHeader from "@/components/TodoHeader.vue";

export default {
  name: "app",
  components: {
    TodoTable,
    TodoHeader
  }
};
</script>

<style lang="scss">
@import url("https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css");

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
