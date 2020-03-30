<template>
  <div class="modal-card" style="width: auto">
    <header class="modal-card-head">
      <p class="modal-card-title">Edit {{ todo.name }}</p>
    </header>
    <section class="modal-card-body">
      <b-field label="Title">
        <b-input
          type="text"
          v-model="name"
          placeholder="Your todo name"
          maxlength="25"
        >
        </b-input>
      </b-field>
      <b-field label="Description">
        <b-input
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
      <button class="button is-primary" @click="editTodo">Save</button>
    </footer>
  </div>
</template>

<script>
export default {
  name: "TodoEditModal",
  props: {
    todo: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      name: "",
      desc: "",
      deadline: null,
      hasDeadline: false
    };
  },
  mounted() {
    this.name = this.todo.name;
    this.desc = this.todo.desc;

    // Kontrola, zda přijaté todo má deadline
    this.hasDeadline = this.todo.deadline === null ? false : true;

    // Pokud nemá deadline, nastaví se datum na dnešní, ať má DateTimePicker co zobrazovat (přijímá objekt typu Date --> .toDate())
    this.deadline =
      this.todo.deadline === null
        ? this.moment().toDate()
        : this.moment(this.todo.deadline).toDate();
  },
  methods: {
    // Po kliku na tlačítko Save
    editTodo() {
      const payload = {
        id: this.todo.id,
        name: this.name,
        desc: this.desc,
        is_completed: this.todo.is_completed,
        deadline: this.hasDeadline ? this.deadline : null
      };
      // Vyvolání metody editTodo v TodoTable.vue se změněnými hodnotami tohoto Todo
      this.$emit("edit-todo", payload);
    }
  }
};
</script>

<style scoped></style>
