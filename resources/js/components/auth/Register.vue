<template>
  <div class="card border-0 shadow-lg overflow-hidden">
    <div class="bg-primary">
      <div class="text-white p-4">
        <h5 class="text-white">
          <b>Selamat Datang</b>
        </h5>
        <p>Silahkan buat akun untuk sistem manajemen dashboard.</p>
      </div>
    </div>
    <div class="card-body">
      <form @submit.prevent="doRegister">
        <div class="mb-3 required-field">
          <label for="name" class="form-label">Nama</label>
          <input 
            id="name" 
            name="name" 
            type="text" 
            class="form-control" 
            placeholder="Enter Name"
            v-model="form.name"
            :class="{ 'is-invalid': hasErrors('name') }"
          />
          <div class="invalid-feedback">
            {{ getErrors("name") }}
          </div>
        </div>
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
        <div class="mb-3 required-field">
          <label for="password" class="form-label">Password Confirmation</label>
          <div class="input-group">
            <input 
              id="password_confirmation" 
              name="password_confirmation" 
              :type="showPasswordConfirmation ? 'text': 'password'"
              class="form-control" 
              placeholder="Enter Password Confirmation"
              v-model="form.password_confirmation"
              :class="{ 'is-invalid': hasErrors('password_confirmation') }"
            />
            <div class="input-group-append">
              <button
                type="button" 
                class="btn btn-input-append border-secondary"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
              >
                  <i
                    :class="showPasswordConfirmation ? 'mdi mdi-eye-off': 'mdi mdi-eye'"
                  ></i>
              </button>
            </div>
          </div>
          <div class="invalid-feedback">
            {{ getErrors("password_confirmation") }}
          </div>
        </div>

        <div class="mt-4">
          <button  
            type="submit"
            class="btn btn-primary w-100"
            :disabled="isLoading"
          >
            Daftar
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
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
      },
      showPassword: false,
      showPasswordConfirmation: false,
      isLoading: false,
      errors: {}
    }
  },
   methods: {
    async doRegister() {
      this.isLoading = true;

      try {
        let response = await axios.post("/register", this.form);
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
