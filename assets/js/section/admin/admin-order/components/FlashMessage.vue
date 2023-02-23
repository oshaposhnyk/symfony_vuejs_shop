<template>
  <v-snackbar
      v-model="snackbar.show"
      :color="snackbar.color"
      :timeout="snackbar.timeout"
      :top="snackbar.top"
      :right="snackbar.right"
      :bottom="snackbar.bottom"
      :left="snackbar.left"
      :vertical="snackbar.vertical"
      :multi-line="snackbar.multiLine"
      :wrap="snackbar.wrap"
      :rounded="snackbar.rounded"
      :elevation="snackbar.elevation"
  >
    {{ snackbar.text }}
    <template v-if="snackbar.actionText">
      <v-btn color="white" text @click="snackbar.action">{{ snackbar.actionText }}</v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  data() {
    return {
      snackbar: {
        show: false,
        text: "",
        color: "",
        timeout: 3000,
        top: true,
        right: true,
        bottom: false,
        left: false,
        vertical: false,
        multiLine: false,
        wrap: false,
        rounded: false,
        elevation: 0,
        actionText: "",
        action: null,
      },
    };
  },
  created() {
  },
  methods: {
    /**
     * Показати флеш повідомлення
     * @param {Object} options Параметри флеш повідомлення
     * {
     *   text: {String} текст повідомлення,
     *   color: {String} колір фону (наприклад, 'success', 'error', 'warning' або 'info'),
     *   timeout: {Number} час, через який флеш повідомлення автоматично закриється,
     *   actionText: {String} текст кнопки дії,
     *   action: {Function} функція, яка виконується при натисканні на кнопку дії
     * }
     */
    showSnackbar(options) {
      this.snackbar.show = false;
      this.snackbar.text = options.text;
      this.snackbar.color = options.color;
      this.snackbar.timeout = options.timeout || 3000;
      this.snackbar.actionText = options.actionText || "";
      this.snackbar.action = options.action || null;
      this.snackbar.show = true;
    },
  },
};
</script>
