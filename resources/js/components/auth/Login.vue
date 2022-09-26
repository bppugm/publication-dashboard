<template>
  <div class="card border-0 shadow-lg overflow-hidden">
    <div class="bg-primary">
      <div class="text-white p-4">
        <h5 class="text-white">
          <b>Selamat Datang</b>
        </h5>
        <p>Silahkan masuk dengan akun yang telah terdaftar untuk melakukan manajemen dashboard.</p>
      </div>
    </div>
    <div class="card-body">
      <form @submit.prevent="doLogin">
        <div class="mb-3 required-field">
          <label for="email" class="form-label fw-bold">Email</label>
          <input
            id="email"
            name="email"
            type="email"
            class="form-control"
            placeholder="Enter Email"
            v-model="form.email"
            :class="{ 'is-invalid': hasErrors('email') }"
          />
          <div class="invalid-feedback">
            {{ getErrors("email") }}
          </div>
        </div>
        <div class="mb-3 required-field">
          <label for="password" class="form-label fw-bold">Password</label>
          <div class="input-group">
            <input
              id="password"
              name="password"
              :type="showPassword ? 'text': 'password'"
              class="form-control icon-append"
              placeholder="Enter Password"
              v-model="form.password"
              :class="{ 'is-invalid': hasErrors('password') }"
            />
            <div
              class="d-flex icon-input-append h-100 px-3"
              @click="showPassword = !showPassword"
            >
              <i class="my-auto" :class="showPassword ? 'mdi mdi-eye-off': 'mdi mdi-eye'"></i>
            </div>
          </div>
          <div class="invalid-feedback">
            {{ getErrors("password") }}
          </div>
        </div>
        <div class="form-group">
          <div class="custom-control custom-checkbox form-check-inline">
              <input
                type="checkbox"
                class="custom-control-input"
                id="remember_me"
                v-model="form.remember"
              />
              <label for="remember_me" class="custom-control-label inline-flex items-center">Remember me</label>
          </div>
        </div>
        <div class="mt-3">
          <button
            type="submit"
            class="btn btn-primary w-100"
            :disabled="isLoading"
          >
            Masuk
          </button>
        </div>
      </form>
      <div class="form-group my-3">
        <div class="flex text-center">
          <a href="#">
            <button 
              type="button"
              class="login-with-google-btn"
            >
              Sign in with Google
            </button>
          </a>   
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        email: null,
        password: null,
        remember: true,
      },
      showPassword: false,
      isLoading: false,
      errors: {}
    }
  },
   methods: {
    async doLogin() {
      this.isLoading = true;

      try {
        let response = await axios.post("/login", this.form);
        window.location = response.data.redirect
      } catch (error) {
        console.log(error)
        this.errors = error.response.data.errors;
        this.form.password = '';
      } finally {
        this.isLoading = false;
      }

    },

    hasErrors(key) {
      if (this.errors[key]) {
        return true;
      }
      return false;
    },
    getErrors(key) {
      if (this.hasErrors(key)) {
        return this.errors[key].join(", ");
      }
      return "";
    },
  }
}
</script>
<style lang="scss" scoped>
.btn-input-append, .btn-input-append:hover, .btn-input-append:focus {
    background-color: #F8FAFC;
    border-color: #CED4DA !important;
    border-left: none;
}
.login-with-google-btn {
  transition: background-color .3s, box-shadow .3s;
    
  padding: 12px 16px 12px 42px;
  border: none;
  border-radius: 3px;
  box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
  
  color: #757575;
  font-size: 14px;
  font-weight: 500;
  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;

  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
  background-color: white;
  background-repeat: no-repeat;
  background-position: 12px 14px;
  
  
  &:hover {
    box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25);
  }
  
  &:active {
    background-color: #eeeeee;
  }
  
  &:focus {
    outline: none;
    box-shadow: 
      0 -1px 0 rgba(0, 0, 0, .04),
      0 2px 4px rgba(0, 0, 0, .25),
      0 0 0 3px #c8dafc;
  }
  
  &:disabled {
    filter: grayscale(100%);
    background-color: #ebebeb;
    box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
    cursor: not-allowed;
  }
}
</style>