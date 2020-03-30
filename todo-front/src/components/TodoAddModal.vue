<template>
  <div class="modal-card" style="width: auto">
    <header class="modal-card-head">
      <p class="modal-card-title">Add Todo</p>
    </header>
    <section class="modal-card-body">
      <b-field label="Title">
        <b-input
          required
          type="text"
          v-model="name"
          placeholder="Your todo name"
          maxlength="25"
          expanded
        >
        </b-input>
      </b-field>
      <b-field label="Description">
        <b-input
          required
          type="textarea"
          v-model="desc"
          placeholder="Your todo description"
          maxlength="250"
        >
        </b-input>
      </b-field>
      <b-field label="Select a date">
        <b-datepicker
          v-if="hasDeadline"
          :show-week-number="true"
          placeholder="Click to select date..."
          icon="calendar-today"
          trap-focus
          v-model="deadline"
          required
          inline
        >
        </b-datepicker>
      </b-field>
      <b-field>
        <!-- Prohozené hodnoty, protože chci checkbox mít zaškrtnutý pokud NENÍ deadline -->
        <b-checkbox
          v-model="hasDeadline"
          :true-value="false"
          :false-value="true"
          >Doesn't have a deadline</b-checkbox
        >
      </b-field>
    </section>
    <footer class="modal-card-foot">
      <button class="button" type="button" @click="$parent.close()">
        Close
      </button>
      <button class="button is-primary" @click="addTodo">Save</button>
    </footer>
  </div>
</template>

<script>
export default {
  name: "TodoAddModal",
  data() {
    return {
      name: "",
      desc: "",
      deadline: null,
      hasDeadline: true
    };
  },
  methods: {
    addTodo() {
      const payload = {
        name: this.name,
        desc: this.desc,
        // Pokud je zaškrnut checkbox že nemá deadline, odesílám null, jinak vybrané datum z DateTimePickeru
        deadline: this.hasDeadline ? this.deadline : null
      };
      // Vyvolání metody addTodo v TodoTable.vue se změněnými hodnotami tohoto Todo
      this.$emit("add-todo", payload);
    }
  }
};
</script>

<style scoped></style>
