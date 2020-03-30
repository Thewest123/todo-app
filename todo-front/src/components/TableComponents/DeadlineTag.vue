<template>
  <div>
    <!-- Výpis datumu -->
    <span class="deadline-text">
      {{ getDateMessage() }}
    </span>
    <!-- Výpis deadline statusu -->
    <span class="tag" :class="getDeadlineClass()">
      {{ getDeadlineMessage() }}
    </span>
  </div>
</template>

<script>
export default {
  name: "DeadlineTag",
  props: {
    todo: {
      type: Object,
      required: true
    }
  },
  methods: {
    getDateMessage() {
      // Pokud nemá nastavenou deadline, nic se nevypisuje, jinak datum v dlouhém formátu 00.00.0000
      if (this.todo.deadline === null) return null;
      else return this.moment(this.todo.deadline).format("L");
    },
    getDeadlineMessage() {
      // Pokud nemá deadline
      if (this.todo.deadline === null) return "Without deadline";

      // Výpočet rozdílu dnů mezi deadline a dnešním datutem (this.moment();)
      const diffDays = this.moment(this.todo.deadline).diff(
        this.moment(),
        "days"
      );

      // Za déle jak 1 den
      if (diffDays > 0) {
        return `In ${diffDays} days`;
        // Méně než 24 hodin
      } else if (diffDays === 0) {
        return "Today !";
        // Již bylo
      } else {
        return `${Math.abs(diffDays)} days late !!`;
      }
    },
    getDeadlineClass() {
      // Pokud je deadline za více jak 10 dní - zelená barva
      if (
        this.moment()
          .add(10, "days")
          .isBefore(this.moment(this.todo.deadline))
      ) {
        return "is-success";
      }
      // Pokud je za méně než 10 dní - oranžová
      else if (this.moment().isBefore(this.moment(this.todo.deadline))) {
        return "is-warning";
      }
      // Pokud je už pozdě - červená
      else if (this.moment().isAfter(this.moment(this.todo.deadline))) {
        return "is-danger";
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.deadline-text {
  padding-right: 0.2em;
  color: rgb(110, 110, 110);
}
</style>
