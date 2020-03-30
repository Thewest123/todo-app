<template>
  <div>
    <section class="section">
      <div class="container">
        <div class="level">
          <b-button class="is-primary" @click="isAddModalActive = true">
            <b-icon icon="plus" />
            <span>Add A Todo</span>
          </b-button>

          <b-button class="is-warning" @click="fetchTodos">
            <b-icon icon="refresh" />
            <span>Refresh</span>
          </b-button>
        </div>

        <b-table
          :data="isEmpty ? [] : todos"
          :default-sort="['deadline', 'des']"
          :loading="isLoading"
          :hoverable="true"
          paginated
          per-page="10"
        >
          <!-- Template pro získaná data -->
          <template slot-scope="todos">
            <b-table-column
              :visible="isIdShown"
              field="id"
              label="ID"
              sortable
              numeric
              width="50"
              >{{ todos.row.id }}</b-table-column
            >

            <b-table-column
              field="name"
              label="Name"
              sortable
              width="250"
              searchable
            >
              {{ todos.row.name }}
            </b-table-column>

            <b-table-column
              field="desc"
              label="Description"
              sortable
              searchable
            >
              {{ todos.row.desc }}
            </b-table-column>

            <b-table-column
              field="is_completed"
              label="Completed"
              width="100"
              sortable
              centered
            >
              <b-checkbox
                v-model="todos.row.is_completed"
                true-value="1"
                false-value="0"
                @input="changeComplete(todos.row, $event)"
              />
            </b-table-column>

            <b-table-column
              field="deadline"
              label="Deadline"
              width="250"
              sortable
            >
              <!-- Tag pro zobrazení deadline statusu -->
              <deadline-tag :todo="todos.row"></deadline-tag>
            </b-table-column>

            <b-table-column label="Edit" width="100" centered>
              <b-button
                type="is-text"
                icon-left="pencil"
                @click="openEditModal(todos.row)"
              ></b-button>
            </b-table-column>

            <b-table-column label="Delete" width="100" centered>
              <b-button
                type="is-text"
                icon-left="delete-outline"
                @click="deleteTodo(todos.row)"
              ></b-button>
            </b-table-column>
          </template>

          <!-- Template, pokud v databázi nic není -->
          <template slot="empty">
            <section class="section">
              <div class="content has-text-grey has-text-centered">
                <p>
                  <b-icon icon="emoticon-sad" size="is-large"></b-icon>
                </p>
                <p>Nothing here.</p>
              </div>
            </section>
          </template>
        </b-table>

        <div class="block">
          <!-- Debug Checkboxes -->
          <div>
            <p>Debug Settings</p>
            <b-icon icon="settings"> </b-icon>
          </div>
          <b-checkbox v-model="isIdShown">Show IDs</b-checkbox>
          <b-checkbox v-model="isEmpty">Set empty</b-checkbox>
          <b-checkbox v-model="isLoading">Set Loading</b-checkbox>
        </div>
      </div>
    </section>

    <!-- Modální okno pro editaci Todo, zobrazí se pokud "isEditModalActive" je true -->
    <b-modal :active.sync="isEditModalActive" has-modal-card>
      <todo-edit-modal
        :todo="selectedTodo"
        @edit-todo="onEditTodo"
      ></todo-edit-modal>
    </b-modal>

    <!-- Modální okno pro vložení nového Todo, zobrazí se pokud "isAddModalActive" je true -->
    <b-modal :active.sync="isAddModalActive" has-modal-card>
      <todo-add-modal @add-todo="onAddTodo"></todo-add-modal>
    </b-modal>
  </div>
</template>

<script>
import TodoEditModal from "@/components/TodoEditModal";
import TodoAddModal from "@/components/TodoAddModal";
import DeadlineTag from "@/components/TableComponents/DeadlineTag";
import axios from "axios";

export default {
  name: "TodoTable",
  components: {
    TodoEditModal,
    TodoAddModal,
    DeadlineTag
  },
  data() {
    return {
      todos: [],
      selectedTodo: {},
      isEditModalActive: false,
      isAddModalActive: false,
      isLoading: true,
      isIdShown: false,
      isEmpty: false
    };
  },
  mounted() {
    this.fetchTodos();
  },
  methods: {
    // Načtení všech Todo ze serveru
    fetchTodos() {
      // Nastavení animace načítání na tabulku
      this.isLoading = true;
      axios
        .get(process.env.VUE_APP_BACKEND_URL + `/get-todos`)
        .then(res => {
          this.todos = res.data;
          console.log("[API] Todos fetched successfully!");

          // Vypnutí animace načítání
          this.isLoading = false;

          // Pokud nebyly načteny žádné Todo, zobrazení informace o tom namísto neexistující/prázdné tabulky
          this.isEmpty = this.todos.length > 0 ? false : true;
        })
        .catch(err => {
          console.log("[API] ERROR: " + err);
          this.isLoading = false;

          // Zobrazení notifikace se zprávou o erroru
          this.showToast(err);
          this.isEmpty = true;
        });
    },
    // Otevření Edit modalu po kliknutí na tlačítko v tabulce
    openEditModal(todo) {
      this.selectedTodo = todo;
      this.isEditModalActive = true;
    },
    // Metoda vyvolaná po kliknu na "Done" z Add modalu
    onAddTodo(todo) {
      axios
        .post(process.env.VUE_APP_BACKEND_URL + `/save-todo`, {
          name: todo.name,
          desc: todo.desc,
          // Pokud není vybraná deadline, ukládá se do db null, jinak datum v unix formátu
          deadline:
            todo.deadline === null ? null : this.moment(todo.deadline).unix()
        })
        .then(res => {
          console.log("[API] Response: " + res.data);
          // Znovunačtení dat
          this.fetchTodos();
          // Zavření modálního okna
          this.isAddModalActive = false;
        })
        .catch(err => {
          console.log("[API] ERROR: " + err);
          // Výpis erroru uživateli
          this.showToast(err);
        });
    },
    // Metoda vyvolaná po kliknu na "Done" z Edit modalu
    onEditTodo(todo) {
      axios
        .post(process.env.VUE_APP_BACKEND_URL + `/update-todo/${todo.id}`, {
          name: todo.name,
          desc: todo.desc,
          is_completed: todo.is_completed,
          deadline:
            todo.deadline === null ? null : this.moment(todo.deadline).unix()
        })
        .then(res => {
          console.log("[API] Response: " + res.data);
          this.fetchTodos();
          this.isEditModalActive = false;
        })
        .catch(err => {
          console.log("[API] ERROR: " + err);
          this.showToast(err);
        });
    },
    // Otevření již předpřivaného dialogu z UI knihovny
    deleteTodo(todo) {
      this.$buefy.dialog.confirm({
        title: `Deleting ${todo.name}`,
        confirmText: "Delete Todo",
        type: "is-danger",
        hasIcon: true,
        message: `Are you sure you want to delete "${todo.name}"? This cannot be undone.`,
        onConfirm: () => {
          axios
            .get(process.env.VUE_APP_BACKEND_URL + `/delete-todo/${todo.id}`)
            .then(res => {
              console.log("[API] Response: " + res.data);
              this.fetchTodos();
            })
            .catch(err => {
              console.log("[API] ERROR: " + err);
              this.showToast(err);
            });
        }
      });
    },
    // Metoda po kliknutí na Checkbox v tabulce
    changeComplete(todo, checkboxValue) {
      // Obrácení hodnoty is_completed
      todo.is_completed = checkboxValue;
      // Vyvolání metody pro editaci, odeslání požadavku na server
      this.onEditTodo(todo);
    },
    // Zobrazení toast zprávy uživateli - využíváno pro zobrazení errorů
    showToast(message) {
      this.$buefy.toast.open({
        duration: 5000,
        message: message.toString(),
        position: "is-bottom",
        type: "is-danger"
      });
    }
  }
};
</script>

<style lang="scss" scoped></style>
