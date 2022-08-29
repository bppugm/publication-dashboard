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
          <label for="email" class="form-label">Email</label>
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
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <input 
              id="password" 
              name="password" 
              :type="showPassword ? 'text': 'password'"
              class="form-control" 
              placeholder="Enter Password"
              v-model="form.password"
              :class="{ 'is-invalid': hasErrors('password') }"
            />
            <div class="input-group-append">
              <button
                type="button" 
                class="btn btn-input-append border-secondary"
                @click="showPassword = !showPassword"
              >
                  <i
                    :class="showPassword ? 'mdi mdi-eye-off': 'mdi mdi-eye'"
                  ></i>
              </button>
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
        window.location = "/"
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
