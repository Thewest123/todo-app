<template>
  <div>
    <b-checkbox v-model="isDarkMode" @input="changeTheme">Dark Mode</b-checkbox>
  </div>
</template>

<script>
/*
 * Work in progress Dark Mode Switch
 * Vzhledem k metodě importu SCSS stylů pro tento UI Framework se mi zatím nepodařilo celkově měnit styly pro použité komponenty
 * Kód jsem ale již napsal, tak ho tu nechávám pro ukázku
 */

export default {
  name: "DarkModeSwitch",
  data() {
    return {
      isDarkMode: false
    };
  },
  mounted() {
    this.initTheme();
  },
  methods: {
    initTheme() {
      this.isDarkMode =
        localStorage.getItem("darkTheme") !== null &&
        localStorage.getItem("darkTheme") === "true";
      this.isDarkMode
        ? document.body.setAttribute("data-theme", "dark")
        : document.body.removeAttribute("data-theme");
    },
    changeTheme(value) {
      if (value) {
        document.body.setAttribute("data-theme", "dark");
        localStorage.setItem("darkTheme", "true");
      } else {
        document.body.removeAttribute("data-theme");
        localStorage.setItem("darkTheme", "false");
      }
    }
  }
};
</script>

<style></style>
