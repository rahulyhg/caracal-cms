<template lang="html">
  <div class="login">
    <div class="loginFormWrapper">
      <LoginForm/>
    </div>
  </div>
</template>

<script>
import LoginForm from "../organisms/loginForm/LoginForm";

export default {
  name: 'login',
  components: {LoginForm}
}
</script>

<style lang="css" scoped>
.login {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-grow: 1;
}
</style>