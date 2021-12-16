<template>
  <div>
    <div class="container">
      <div
        class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-5"
      >
        <div class="login-header">
          <h1>Login to the dashboard</h1>
        </div>
        Email:
        <Input type="email" v-model="data.email" placeholder="Enter your email..." />
        <br />
        Password:
        <Input
          type="password"
          v-model="data.password"
          placeholder="Enter your password..."
        />
        <br /><br />
        <div class="login-footer">
          <Button
            type="primary"
            :disabled="isLogging"
            :loading="isLogging"
            @click="login"
            >{{ isLogging ? "Logging" : "Login" }}</Button
          >
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: {
        email: "",
        password: "",
      },
      isLogging: false,
    };
  },
  methods: {
    async login() {
      if (this.data.email.trim() === "") return this.error("Email is required");
      if (this.data.password.trim() === "") return this.error("Password is required");
      const res = await this.callApi("POST", "app/admin_login", this.data);

      this.isLogging = true;
      if (res.status === 200) {
        this.success(res.data.msg);
      } else {
        if (res.status === 401) {
          this.info(res.data.msg);
        } else if (res.status === 422) {
          for (let index in res.data.errors) {
              this.info(res.data.errors[index][0]);
          }
        } else {
            this.swr();
        }
      }

      this.isLogging = false;
    },
  },
};
</script>
<style>
._1adminOverveiw_table_recent {
  margin: 0 auto;
  margin-top: 30vh;
}

.login-footer {
  text-align: center;
}

.login-header {
  text-align: center;
  color: black;
  margin-bottom: 25px;
}
</style>
