const { createApp } = Vue;

createApp({
    data() {
        return {
            message: "MIRAI-LABS_forinoue 起動中 🚀"
        };
    },
    mounted() {
        console.log(this.message);
    }
}).mount("#app");
